<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Bed;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdmissionCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Admissions',
            'resource' => 'admin.admissions',
            'items'    => Admission::with(['patient', 'doctor', 'bed.room'])->latest('admission_date')->paginate(20),
            'columns'  => ['patient.name', 'doctor.name', 'bed.bed_number', 'status', 'admission_date'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Admission',
            'resource' => 'admin.admissions',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        DB::transaction(function () use ($validated) {
            Admission::create($validated);

            if ($validated['status'] === 'admitted') {
                Bed::where('id', $validated['bed_id'])->update(['status' => 'occupied']);
            }
        });

        return redirect()->route('admin.admissions.index')->with('success', 'Admission created.');
    }

    public function show(Admission $admission): View
    {
        $admission->load(['patient', 'doctor', 'bed.room']);

        return view('admin.crud.show', [
            'title'    => 'Admission',
            'resource' => 'admin.admissions',
            'item'     => $admission,
            'columns'  => ['patient.name', 'doctor.name', 'bed.room.room_number', 'bed.bed_number', 'admission_date', 'discharge_date', 'status', 'reason_for_admission'],
        ]);
    }

    public function edit(Admission $admission): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Admission',
            'resource' => 'admin.admissions',
            'item'     => $admission,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, Admission $admission): RedirectResponse
    {
        $validated = $this->validated($request);

        DB::transaction(function () use ($validated, $admission) {
            $previousBedId = $admission->bed_id;
            $admission->update($validated);

            if ($previousBedId !== (int) $validated['bed_id']) {
                Bed::where('id', $previousBedId)->update(['status' => 'available']);
            }

            if (in_array($validated['status'], ['discharged', 'transferred'], true)) {
                Bed::where('id', $validated['bed_id'])->update(['status' => 'available']);
            } elseif ($validated['status'] === 'admitted') {
                Bed::where('id', $validated['bed_id'])->update(['status' => 'occupied']);
            }
        });

        return redirect()->route('admin.admissions.index')->with('success', 'Admission updated.');
    }

    public function destroy(Admission $admission): RedirectResponse
    {
        DB::transaction(function () use ($admission) {
            if ($admission->status === 'admitted') {
                Bed::where('id', $admission->bed_id)->update(['status' => 'available']);
            }
            $admission->delete();
        });

        return redirect()->route('admin.admissions.index')->with('success', 'Admission deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'patient_id'           => 'required|exists:users,id',
            'doctor_id'            => 'required|exists:users,id',
            'bed_id'               => 'required|exists:beds,id',
            'admission_date'       => 'required|date',
            'discharge_date'       => 'nullable|date',
            'status'               => 'required|in:admitted,discharged,transferred',
            'reason_for_admission' => 'required|string',
        ]);
    }

    private function fields(): array
    {
        return [
            ['name' => 'patient_id', 'label' => 'Patient', 'type' => 'select', 'required' => true, 'options' => User::role('patient')->orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'doctor_id', 'label' => 'Doctor', 'type' => 'select', 'required' => true, 'options' => User::role('doctor')->orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'bed_id', 'label' => 'Bed', 'type' => 'select', 'required' => true, 'options' => Bed::with('room')->get()->mapWithKeys(fn (Bed $bed) => [$bed->id => trim(($bed->room->room_number ?? 'Room').' / '.$bed->bed_number)])->all()],
            ['name' => 'admission_date', 'label' => 'Admission date', 'type' => 'datetime-local', 'required' => true],
            ['name' => 'discharge_date', 'label' => 'Discharge date', 'type' => 'datetime-local', 'required' => false],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['admitted' => 'Admitted', 'discharged' => 'Discharged', 'transferred' => 'Transferred']],
            ['name' => 'reason_for_admission', 'label' => 'Reason for admission', 'type' => 'textarea', 'required' => true],
        ];
    }
}
