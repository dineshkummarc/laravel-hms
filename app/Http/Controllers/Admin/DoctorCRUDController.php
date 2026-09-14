<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DoctorProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DoctorCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Doctors',
            'resource' => 'admin.doctors',
            'items'    => User::role('doctor')->with('doctorProfile.department')->latest()->paginate(20),
            'columns'  => ['name', 'email', 'doctorProfile.specialty', 'doctorProfile.department.name', 'doctorProfile.consultation_fee'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Doctor',
            'resource' => 'admin.doctors',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => $validated['password'],
                'uid'       => 'usr_'.Str::lower(Str::random(12)),
                'is_active' => true,
            ]);
            $user->assignRole('doctor');

            DoctorProfile::create([
                'user_id'          => $user->id,
                'specialty'        => $validated['specialty'],
                'department_id'    => $validated['department_id'],
                'qualification'    => $validated['qualification'] ?? null,
                'room_number'      => $validated['room_number'] ?? null,
                'consultation_fee' => $validated['consultation_fee'] ?? null,
            ]);
        });

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor created.');
    }

    public function show(User $doctor): View
    {
        $doctor->load('doctorProfile.department');

        return view('admin.crud.show', [
            'title'    => 'Doctor',
            'resource' => 'admin.doctors',
            'item'     => $doctor,
            'columns'  => ['name', 'email', 'doctorProfile.specialty', 'doctorProfile.department.name', 'doctorProfile.qualification', 'doctorProfile.room_number', 'doctorProfile.consultation_fee'],
        ]);
    }

    public function edit(User $doctor): View
    {
        $profile = $doctor->doctorProfile;
        if ($profile) {
            $doctor->setAttribute('specialty', $profile->specialty);
            $doctor->setAttribute('department_id', $profile->department_id);
            $doctor->setAttribute('qualification', $profile->qualification);
            $doctor->setAttribute('room_number', $profile->room_number);
            $doctor->setAttribute('consultation_fee', $profile->consultation_fee);
        }

        return view('admin.crud.edit', [
            'title'    => 'Doctor',
            'resource' => 'admin.doctors',
            'item'     => $doctor,
            'fields'   => $this->fields(false),
        ]);
    }

    public function update(Request $request, User $doctor): RedirectResponse
    {
        $validated = $this->validated($request, $doctor->id);

        DB::transaction(function () use ($validated, $doctor) {
            $payload = [
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ];
            if (!empty($validated['password'])) {
                $payload['password'] = $validated['password'];
            }
            $doctor->update($payload);

            $doctor->doctorProfile()->updateOrCreate(
                ['user_id' => $doctor->id],
                [
                    'specialty'        => $validated['specialty'],
                    'department_id'    => $validated['department_id'],
                    'qualification'    => $validated['qualification'] ?? null,
                    'room_number'      => $validated['room_number'] ?? null,
                    'consultation_fee' => $validated['consultation_fee'] ?? null,
                ]
            );
        });

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated.');
    }

    public function destroy(User $doctor): RedirectResponse
    {
        $doctor->delete();

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted.');
    }

    private function validated(Request $request, ?int $userId = null): array
    {
        return $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|max:255|unique:users,email,'.($userId ?? 'NULL'),
            'password'         => ($userId ? 'nullable' : 'required').'|string|min:8',
            'specialty'        => 'required|string|max:255',
            'department_id'    => 'required|exists:departments,id',
            'qualification'    => 'nullable|string',
            'room_number'      => 'nullable|string|max:255',
            'consultation_fee' => 'nullable|numeric|min:0',
        ]);
    }

    private function fields(bool $passwordRequired = true): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
            ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'required' => $passwordRequired],
            ['name' => 'specialty', 'label' => 'Specialty', 'type' => 'text', 'required' => true],
            [
                'name'     => 'department_id',
                'label'    => 'Department',
                'type'     => 'select',
                'required' => true,
                'options'  => Department::orderBy('name')->pluck('name', 'id')->all(),
            ],
            ['name' => 'qualification', 'label' => 'Qualification', 'type' => 'textarea', 'required' => false],
            ['name' => 'room_number', 'label' => 'Room number', 'type' => 'text', 'required' => false],
            ['name' => 'consultation_fee', 'label' => 'Consultation fee', 'type' => 'number', 'required' => false],
        ];
    }
}
