<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Rooms',
            'resource' => 'admin.rooms',
            'items'    => Room::with('department')->latest()->paginate(20),
            'columns'  => ['room_number', 'department.name', 'type'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Room',
            'resource' => 'admin.rooms',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Room::create($this->validated($request));

        return redirect()->route('admin.rooms.index')->with('success', 'Room created.');
    }

    public function show(Room $room): View
    {
        $room->load('department');

        return view('admin.crud.show', [
            'title'    => 'Room',
            'resource' => 'admin.rooms',
            'item'     => $room,
            'columns'  => ['room_number', 'department.name', 'type'],
        ]);
    }

    public function edit(Room $room): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Room',
            'resource' => 'admin.rooms',
            'item'     => $room,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $room->update($this->validated($request));

        return redirect()->route('admin.rooms.index')->with('success', 'Room updated.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'room_number'   => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'type'          => 'required|string|max:255',
        ]);
    }

    private function fields(): array
    {
        return [
            ['name' => 'room_number', 'label' => 'Room number', 'type' => 'text', 'required' => true],
            ['name' => 'department_id', 'label' => 'Department', 'type' => 'select', 'required' => true, 'options' => Department::orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'type', 'label' => 'Type', 'type' => 'select', 'required' => true, 'options' => ['general' => 'General', 'icu' => 'ICU', 'ccu' => 'CCU', 'cabin' => 'Cabin']],
        ];
    }
}
