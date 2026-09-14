<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BloodUnitCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Blood units',
            'resource' => 'admin.blood-units',
            'items'    => BloodUnit::latest()->paginate(20),
            'columns'  => ['bag_number', 'blood_group', 'component', 'units', 'donor_name', 'expires_on', 'status'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Blood unit',
            'resource' => 'admin.blood-units',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        BloodUnit::create($this->validated($request));

        return redirect()->route('admin.blood-units.index')->with('success', 'Blood unit recorded.');
    }

    public function show(BloodUnit $blood_unit): View
    {
        return view('admin.crud.show', [
            'title'    => 'Blood unit',
            'resource' => 'admin.blood-units',
            'item'     => $blood_unit,
            'columns'  => ['bag_number', 'blood_group', 'component', 'units', 'donor_name', 'donor_phone', 'collected_on', 'expires_on', 'status'],
        ]);
    }

    public function edit(BloodUnit $blood_unit): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Blood unit',
            'resource' => 'admin.blood-units',
            'item'     => $blood_unit,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, BloodUnit $blood_unit): RedirectResponse
    {
        $blood_unit->update($this->validated($request));

        return redirect()->route('admin.blood-units.index')->with('success', 'Blood unit updated.');
    }

    public function destroy(BloodUnit $blood_unit): RedirectResponse
    {
        $blood_unit->delete();

        return redirect()->route('admin.blood-units.index')->with('success', 'Blood unit deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'bag_number' => 'required|string|max:255',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'component' => 'required|in:whole_blood,plasma,platelets,rbc',
            'units' => 'required|integer|min:1',
            'donor_name' => 'nullable|string|max:255',
            'donor_phone' => 'nullable|string|max:30',
            'collected_on' => 'required|date',
            'expires_on' => 'required|date|after:collected_on',
            'status' => 'required|in:available,reserved,issued,expired,discarded',
        ]);
    }

    private function fields(): array
    {
        return [
            ['name' => 'bag_number', 'label' => 'Bag number', 'type' => 'text', 'required' => true],
            ['name' => 'blood_group', 'label' => 'Blood group', 'type' => 'select', 'required' => true, 'options' => array_combine(['A+','A-','B+','B-','AB+','AB-','O+','O-'], ['A+','A-','B+','B-','AB+','AB-','O+','O-'])],
            ['name' => 'component', 'label' => 'Component', 'type' => 'select', 'required' => true, 'options' => ['whole_blood' => 'Whole blood', 'plasma' => 'Plasma', 'platelets' => 'Platelets', 'rbc' => 'RBC']],
            ['name' => 'units', 'label' => 'Units', 'type' => 'number', 'required' => true],
            ['name' => 'donor_name', 'label' => 'Donor name', 'type' => 'text', 'required' => false],
            ['name' => 'donor_phone', 'label' => 'Donor phone', 'type' => 'text', 'required' => false],
            ['name' => 'collected_on', 'label' => 'Collected on', 'type' => 'date', 'required' => true],
            ['name' => 'expires_on', 'label' => 'Expires on', 'type' => 'date', 'required' => true],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['available' => 'Available', 'reserved' => 'Reserved', 'issued' => 'Issued', 'expired' => 'Expired', 'discarded' => 'Discarded']],
        ];
    }
}
