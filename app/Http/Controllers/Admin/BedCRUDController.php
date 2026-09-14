<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BedCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Beds',
            'resource' => 'admin.beds',
            'items'    => Bed::with('room')->latest()->paginate(20),
            'columns'  => ['bed_number', 'room.room_number', 'status', 'daily_rate'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Bed',
            'resource' => 'admin.beds',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Bed::create($this->validated($request));

        return redirect()->route('admin.beds.index')->with('success', 'Bed created.');
    }

    public function show(Bed $bed): View
    {
        $bed->load('room');

        return view('admin.crud.show', [
            'title'    => 'Bed',
            'resource' => 'admin.beds',
            'item'     => $bed,
            'columns'  => ['bed_number', 'room.room_number', 'status', 'daily_rate'],
        ]);
    }

    public function edit(Bed $bed): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Bed',
            'resource' => 'admin.beds',
            'item'     => $bed,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, Bed $bed): RedirectResponse
    {
        $bed->update($this->validated($request));

        return redirect()->route('admin.beds.index')->with('success', 'Bed updated.');
    }

    public function destroy(Bed $bed): RedirectResponse
    {
        $bed->delete();

        return redirect()->route('admin.beds.index')->with('success', 'Bed deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'room_id'    => 'required|exists:rooms,id',
            'bed_number' => 'required|string|max:255',
            'status'     => 'required|in:available,occupied,maintenance',
            'daily_rate' => 'required|numeric|min:0',
        ]);
    }

    private function fields(): array
    {
        return [
            ['name' => 'room_id', 'label' => 'Room', 'type' => 'select', 'required' => true, 'options' => Room::orderBy('room_number')->pluck('room_number', 'id')->all()],
            ['name' => 'bed_number', 'label' => 'Bed number', 'type' => 'text', 'required' => true],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['available' => 'Available', 'occupied' => 'Occupied', 'maintenance' => 'Maintenance']],
            ['name' => 'daily_rate', 'label' => 'Daily rate', 'type' => 'number', 'required' => true],
        ];
    }
}
