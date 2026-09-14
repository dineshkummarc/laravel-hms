<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicineCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Medicines',
            'resource' => 'admin.medicines',
            'items'    => Medicine::latest()->paginate(20),
            'columns'  => ['name', 'generic_name', 'category', 'stock_qty', 'reorder_level', 'unit_price', 'expiry_date', 'is_active'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Medicine',
            'resource' => 'admin.medicines',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        Medicine::create($data);

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine created.');
    }

    public function show(Medicine $medicine): View
    {
        return view('admin.crud.show', [
            'title'    => 'Medicine',
            'resource' => 'admin.medicines',
            'item'     => $medicine,
            'columns'  => ['name', 'generic_name', 'category', 'unit', 'unit_price', 'stock_qty', 'reorder_level', 'expiry_date', 'manufacturer', 'is_active'],
        ]);
    }

    public function edit(Medicine $medicine): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Medicine',
            'resource' => 'admin.medicines',
            'item'     => $medicine,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, Medicine $medicine): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $medicine->update($data);

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine updated.');
    }

    public function destroy(Medicine $medicine): RedirectResponse
    {
        $medicine->delete();

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'stock_qty' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
            'manufacturer' => 'nullable|string|max:255',
        ]);
    }

    private function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
            ['name' => 'generic_name', 'label' => 'Generic name', 'type' => 'text', 'required' => false],
            ['name' => 'category', 'label' => 'Category', 'type' => 'text', 'required' => false],
            ['name' => 'unit', 'label' => 'Unit', 'type' => 'text', 'required' => true],
            ['name' => 'unit_price', 'label' => 'Unit price', 'type' => 'number', 'required' => true],
            ['name' => 'stock_qty', 'label' => 'Stock qty', 'type' => 'number', 'required' => true],
            ['name' => 'reorder_level', 'label' => 'Reorder level', 'type' => 'number', 'required' => true],
            ['name' => 'expiry_date', 'label' => 'Expiry date', 'type' => 'date', 'required' => false],
            ['name' => 'manufacturer', 'label' => 'Manufacturer', 'type' => 'text', 'required' => false],
            ['name' => 'is_active', 'label' => 'Active', 'type' => 'checkbox', 'required' => false],
        ];
    }
}
