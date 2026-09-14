<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'     => 'Users',
            'resource'  => 'admin.users',
            'items'     => User::with('roles')->latest()->paginate(20),
            'columns'   => ['name', 'email', 'is_active', 'roles'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'User',
            'resource' => 'admin.users',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email',
            'password'  => 'required|string|min:8',
            'role'      => 'required|exists:roles,name',
            'is_active' => 'sometimes|boolean',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => $validated['password'],
            'uid'       => 'usr_'.Str::lower(Str::random(12)),
            'is_active' => $request->boolean('is_active'),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function show(User $user): View
    {
        $user->load('roles');

        return view('admin.crud.show', [
            'title'    => 'User',
            'resource' => 'admin.users',
            'item'     => $user,
            'columns'  => ['uid', 'name', 'email', 'is_active', 'roles'],
        ]);
    }

    public function edit(User $user): View
    {
        $user->setAttribute('role', $user->roles->pluck('name')->first());

        return view('admin.crud.edit', [
            'title'    => 'User',
            'resource' => 'admin.users',
            'item'     => $user,
            'fields'   => $this->fields(false),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email,'.$user->id,
            'password'  => 'nullable|string|min:8',
            'role'      => 'required|exists:roles,name',
            'is_active' => 'sometimes|boolean',
        ]);

        $payload = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'is_active' => $request->boolean('is_active'),
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        $user->update($payload);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return back()->with('error', 'Cannot delete the last admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    private function fields(bool $passwordRequired = true): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
            ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'required' => $passwordRequired],
            [
                'name'     => 'role',
                'label'    => 'Role',
                'type'     => 'select',
                'required' => true,
                'options'  => Role::orderBy('name')->pluck('name', 'name')->all(),
            ],
            ['name' => 'is_active', 'label' => 'Active', 'type' => 'checkbox', 'required' => false],
        ];
    }
}
