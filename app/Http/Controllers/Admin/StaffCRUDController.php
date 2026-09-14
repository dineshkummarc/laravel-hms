<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class StaffCRUDController extends Controller
{
    private array $staffRoles = [
        'receptionist',
        'cashier',
        'lab_technician',
        'report_giver',
        'nurse',
        'pharmacist',
    ];

    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Staff',
            'resource' => 'admin.staff-profiles',
            'items'    => User::role($this->staffRoles)->with('staffProfile')->latest()->paginate(20),
            'columns'  => ['name', 'email', 'staffProfile.designation', 'staffProfile.shift', 'staffProfile.joining_date'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Staff member',
            'resource' => 'admin.staff-profiles',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        DB::transaction(function () use ($validated) {
            Role::findOrCreate($validated['role']);

            $user = User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => $validated['password'],
                'uid'       => 'usr_'.Str::lower(Str::random(12)),
                'is_active' => true,
            ]);
            $user->assignRole($validated['role']);

            StaffProfile::create([
                'user_id'      => $user->id,
                'designation'  => $validated['designation'] ?? null,
                'shift'        => $validated['shift'] ?? 'Day',
                'joining_date' => $validated['joining_date'] ?? null,
            ]);
        });

        return redirect()->route('admin.staff-profiles.index')->with('success', 'Staff member created.');
    }

    public function show(User $staff_profile): View
    {
        $staff_profile->load(['roles', 'staffProfile']);

        return view('admin.crud.show', [
            'title'    => 'Staff member',
            'resource' => 'admin.staff-profiles',
            'item'     => $staff_profile,
            'columns'  => ['name', 'email', 'roles', 'staffProfile.designation', 'staffProfile.shift', 'staffProfile.joining_date'],
        ]);
    }

    public function edit(User $staff_profile): View
    {
        $profile = $staff_profile->staffProfile;
        if ($profile) {
            $staff_profile->setAttribute('designation', $profile->designation);
            $staff_profile->setAttribute('shift', $profile->shift);
            $staff_profile->setAttribute('joining_date', $profile->joining_date);
        }
        $staff_profile->setAttribute('role', $staff_profile->roles->pluck('name')->first());

        return view('admin.crud.edit', [
            'title'    => 'Staff member',
            'resource' => 'admin.staff-profiles',
            'item'     => $staff_profile,
            'fields'   => $this->fields(false),
        ]);
    }

    public function update(Request $request, User $staff_profile): RedirectResponse
    {
        $validated = $this->validated($request, $staff_profile->id);

        DB::transaction(function () use ($validated, $staff_profile) {
            Role::findOrCreate($validated['role']);

            $payload = [
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ];
            if (!empty($validated['password'])) {
                $payload['password'] = $validated['password'];
            }
            $staff_profile->update($payload);
            $staff_profile->syncRoles([$validated['role']]);

            $staff_profile->staffProfile()->updateOrCreate(
                ['user_id' => $staff_profile->id],
                [
                    'designation'  => $validated['designation'] ?? null,
                    'shift'        => $validated['shift'] ?? 'Day',
                    'joining_date' => $validated['joining_date'] ?? null,
                ]
            );
        });

        return redirect()->route('admin.staff-profiles.index')->with('success', 'Staff member updated.');
    }

    public function destroy(User $staff_profile): RedirectResponse
    {
        $staff_profile->delete();

        return redirect()->route('admin.staff-profiles.index')->with('success', 'Staff member deleted.');
    }

    private function validated(Request $request, ?int $userId = null): array
    {
        return $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,'.($userId ?? 'NULL'),
            'password'     => ($userId ? 'nullable' : 'required').'|string|min:8',
            'role'         => 'required|in:'.implode(',', $this->staffRoles),
            'designation'  => 'nullable|string|max:255',
            'shift'        => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
        ]);
    }

    private function fields(bool $passwordRequired = true): array
    {
        $roleOptions = [];
        foreach ($this->staffRoles as $role) {
            $roleOptions[$role] = Str::headline($role);
        }

        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
            ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'required' => $passwordRequired],
            ['name' => 'role', 'label' => 'Role', 'type' => 'select', 'required' => true, 'options' => $roleOptions],
            ['name' => 'designation', 'label' => 'Designation', 'type' => 'text', 'required' => false],
            ['name' => 'shift', 'label' => 'Shift', 'type' => 'text', 'required' => false],
            ['name' => 'joining_date', 'label' => 'Joining date', 'type' => 'date', 'required' => false],
        ];
    }
}
