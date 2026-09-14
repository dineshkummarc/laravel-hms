<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PatientCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Patients',
            'resource' => 'admin.patients',
            'items'    => User::role('patient')->with('patientProfile')->latest()->paginate(20),
            'columns'  => ['name', 'email', 'patientProfile.age', 'patientProfile.gender', 'patientProfile.blood_group'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Patient',
            'resource' => 'admin.patients',
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
                'uid'       => 'pat_'.Str::lower(Str::random(12)),
                'is_active' => true,
            ]);
            $user->assignRole('patient');

            PatientProfile::create([
                'user_id'           => $user->id,
                'age'               => $validated['age'],
                'gender'            => $validated['gender'],
                'blood_group'       => $validated['blood_group'] ?? null,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'address'           => $validated['address'] ?? null,
            ]);
        });

        return redirect()->route('admin.patients.index')->with('success', 'Patient created.');
    }

    public function show(User $patient): View
    {
        $patient->load('patientProfile');

        return view('admin.crud.show', [
            'title'    => 'Patient',
            'resource' => 'admin.patients',
            'item'     => $patient,
            'columns'  => ['name', 'email', 'patientProfile.age', 'patientProfile.gender', 'patientProfile.blood_group', 'patientProfile.emergency_contact', 'patientProfile.address'],
        ]);
    }

    public function edit(User $patient): View
    {
        $profile = $patient->patientProfile;
        if ($profile) {
            $patient->setAttribute('age', $profile->age);
            $patient->setAttribute('gender', $profile->gender);
            $patient->setAttribute('blood_group', $profile->blood_group);
            $patient->setAttribute('emergency_contact', $profile->emergency_contact);
            $patient->setAttribute('address', $profile->address);
        }

        return view('admin.crud.edit', [
            'title'    => 'Patient',
            'resource' => 'admin.patients',
            'item'     => $patient,
            'fields'   => $this->fields(false),
        ]);
    }

    public function update(Request $request, User $patient): RedirectResponse
    {
        $validated = $this->validated($request, $patient->id);

        DB::transaction(function () use ($validated, $patient) {
            $payload = [
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ];
            if (!empty($validated['password'])) {
                $payload['password'] = $validated['password'];
            }
            $patient->update($payload);

            $patient->patientProfile()->updateOrCreate(
                ['user_id' => $patient->id],
                [
                    'age'               => $validated['age'],
                    'gender'            => $validated['gender'],
                    'blood_group'       => $validated['blood_group'] ?? null,
                    'emergency_contact' => $validated['emergency_contact'] ?? null,
                    'address'           => $validated['address'] ?? null,
                ]
            );
        });

        return redirect()->route('admin.patients.index')->with('success', 'Patient updated.');
    }

    public function destroy(User $patient): RedirectResponse
    {
        $patient->delete();

        return redirect()->route('admin.patients.index')->with('success', 'Patient deleted.');
    }

    private function validated(Request $request, ?int $userId = null): array
    {
        return $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|max:255|unique:users,email,'.($userId ?? 'NULL'),
            'password'          => ($userId ? 'nullable' : 'required').'|string|min:8',
            'age'               => 'required|integer|min:0|max:130',
            'gender'            => 'required|in:male,female,other',
            'blood_group'       => 'nullable|string|max:5',
            'emergency_contact' => 'nullable|string|max:255',
            'address'           => 'nullable|string',
        ]);
    }

    private function fields(bool $passwordRequired = true): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
            ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'required' => $passwordRequired],
            ['name' => 'age', 'label' => 'Age', 'type' => 'number', 'required' => true],
            [
                'name'     => 'gender',
                'label'    => 'Gender',
                'type'     => 'select',
                'required' => true,
                'options'  => ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'],
            ],
            ['name' => 'blood_group', 'label' => 'Blood group', 'type' => 'text', 'required' => false],
            ['name' => 'emergency_contact', 'label' => 'Emergency contact', 'type' => 'text', 'required' => false],
            ['name' => 'address', 'label' => 'Address', 'type' => 'textarea', 'required' => false],
        ];
    }
}
