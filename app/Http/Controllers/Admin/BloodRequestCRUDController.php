<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\BloodUnit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BloodRequestCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Blood requests',
            'resource' => 'admin.blood-requests',
            'items'    => BloodRequest::with('patient')->latest()->paginate(20),
            'columns'  => ['patient.name', 'requester_name', 'blood_group', 'component', 'units_needed', 'urgency', 'status'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Blood request',
            'resource' => 'admin.blood-requests',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        BloodRequest::create($this->validated($request));

        return redirect()->route('admin.blood-requests.index')->with('success', 'Blood request created.');
    }

    public function show(BloodRequest $blood_request): View
    {
        $blood_request->load(['patient', 'fulfilledUnit']);

        return view('admin.crud.show', [
            'title'    => 'Blood request',
            'resource' => 'admin.blood-requests',
            'item'     => $blood_request,
            'columns'  => ['patient.name', 'requester_name', 'blood_group', 'component', 'units_needed', 'urgency', 'status', 'reason'],
        ]);
    }

    public function edit(BloodRequest $blood_request): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Blood request',
            'resource' => 'admin.blood-requests',
            'item'     => $blood_request,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, BloodRequest $blood_request): RedirectResponse
    {
        $blood_request->update($this->validated($request));

        return redirect()->route('admin.blood-requests.index')->with('success', 'Blood request updated.');
    }

    public function destroy(BloodRequest $blood_request): RedirectResponse
    {
        $blood_request->delete();

        return redirect()->route('admin.blood-requests.index')->with('success', 'Blood request deleted.');
    }

    public function fulfill(BloodRequest $blood_request): RedirectResponse
    {
        if ($blood_request->status === 'fulfilled') {
            return back()->with('error', 'This request is already fulfilled.');
        }

        $unit = BloodUnit::where('blood_group', $blood_request->blood_group)
            ->where('component', $blood_request->component)
            ->where('status', 'available')
            ->whereDate('expires_on', '>=', now())
            ->orderBy('expires_on')
            ->first();

        if (! $unit) {
            return back()->with('error', 'No matching available blood unit.');
        }

        DB::transaction(function () use ($blood_request, $unit) {
            $unit->update(['status' => 'issued']);
            $blood_request->update([
                'status' => 'fulfilled',
                'fulfilled_unit_id' => $unit->id,
            ]);
        });

        return back()->with('success', 'Issued bag '.$unit->bag_number.' to this request.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'patient_id' => 'nullable|exists:users,id',
            'requester_name' => 'nullable|string|max:255',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'component' => 'required|in:whole_blood,plasma,platelets,rbc',
            'units_needed' => 'required|integer|min:1',
            'urgency' => 'required|in:routine,urgent,emergency',
            'status' => 'required|in:pending,approved,fulfilled,rejected',
            'reason' => 'nullable|string',
        ]);
    }

    private function fields(): array
    {
        return [
            ['name' => 'patient_id', 'label' => 'Patient', 'type' => 'select', 'required' => false, 'options' => User::role('patient')->orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'requester_name', 'label' => 'Requester name', 'type' => 'text', 'required' => false],
            ['name' => 'blood_group', 'label' => 'Blood group', 'type' => 'select', 'required' => true, 'options' => array_combine(['A+','A-','B+','B-','AB+','AB-','O+','O-'], ['A+','A-','B+','B-','AB+','AB-','O+','O-'])],
            ['name' => 'component', 'label' => 'Component', 'type' => 'select', 'required' => true, 'options' => ['whole_blood' => 'Whole blood', 'plasma' => 'Plasma', 'platelets' => 'Platelets', 'rbc' => 'RBC']],
            ['name' => 'units_needed', 'label' => 'Units needed', 'type' => 'number', 'required' => true],
            ['name' => 'urgency', 'label' => 'Urgency', 'type' => 'select', 'required' => true, 'options' => ['routine' => 'Routine', 'urgent' => 'Urgent', 'emergency' => 'Emergency']],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['pending' => 'Pending', 'approved' => 'Approved', 'fulfilled' => 'Fulfilled', 'rejected' => 'Rejected']],
            ['name' => 'reason', 'label' => 'Reason', 'type' => 'textarea', 'required' => false],
        ];
    }
}
