<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\LabTest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LabTestCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Lab tests',
            'resource' => 'admin.lab-tests',
            'items'    => LabTest::with('department')->latest()->paginate(20),
            'columns'  => ['name', 'department.name', 'cost', 'is_active'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Lab test',
            'resource' => 'admin.lab-tests',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        LabTest::create($validated);

        return redirect()->route('admin.lab-tests.index')->with('success', 'Lab test created.');
    }

    public function show(LabTest $lab_test): View
    {
        $lab_test->load('department');

        return view('admin.crud.show', [
            'title'    => 'Lab test',
            'resource' => 'admin.lab-tests',
            'item'     => $lab_test,
            'columns'  => ['name', 'slug', 'department.name', 'cost', 'is_active'],
        ]);
    }

    public function edit(LabTest $lab_test): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Lab test',
            'resource' => 'admin.lab-tests',
            'item'     => $lab_test,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, LabTest $lab_test): RedirectResponse
    {
        $validated = $this->validated($request, $lab_test->id);
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        $lab_test->update($validated);

        return redirect()->route('admin.lab-tests.index')->with('success', 'Lab test updated.');
    }

    public function destroy(LabTest $lab_test): RedirectResponse
    {
        $lab_test->delete();

        return redirect()->route('admin.lab-tests.index')->with('success', 'Lab test deleted.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name'          => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'cost'          => 'required|numeric|min:0',
            'slug'          => 'nullable|string|max:255|unique:lab_tests,slug,'.($id ?? 'NULL'),
            'is_active'     => 'sometimes|boolean',
        ]);
    }

    private function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
            ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'required' => false],
            ['name' => 'department_id', 'label' => 'Department', 'type' => 'select', 'required' => true, 'options' => Department::orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'cost', 'label' => 'Cost', 'type' => 'number', 'required' => true],
            ['name' => 'is_active', 'label' => 'Active', 'type' => 'checkbox', 'required' => false],
        ];
    }
}
