<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DepartmentCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Departments',
            'resource' => 'admin.departments',
            'items'    => Department::withCount(['doctors', 'rooms'])->latest()->paginate(20),
            'columns'  => ['name', 'slug', 'doctors_count', 'rooms_count'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Department',
            'resource' => 'admin.departments',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'slug' => 'nullable|string|max:255|unique:departments,slug',
        ]);
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        Department::create($validated);

        return redirect()->route('admin.departments.index')->with('success', 'Department created.');
    }

    public function show(Department $department): View
    {
        return view('admin.crud.show', [
            'title'    => 'Department',
            'resource' => 'admin.departments',
            'item'     => $department,
            'columns'  => ['name', 'slug'],
        ]);
    }

    public function edit(Department $department): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Department',
            'resource' => 'admin.departments',
            'item'     => $department,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,'.$department->id,
            'slug' => 'nullable|string|max:255|unique:departments,slug,'.$department->id,
        ]);
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        $department->update($validated);

        return redirect()->route('admin.departments.index')->with('success', 'Department updated.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        if ($department->doctors()->exists()) {
            return back()->with('error', 'Cannot delete a department that still has doctors.');
        }

        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Department deleted.');
    }

    private function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
            ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'required' => false],
        ];
    }
}
