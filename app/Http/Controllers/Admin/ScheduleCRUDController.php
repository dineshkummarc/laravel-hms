<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Doctor schedules',
            'resource' => 'admin.schedules',
            'items'    => Schedule::with('doctor')->latest()->paginate(20),
            'columns'  => ['doctor.name', 'day_of_week', 'start_time', 'end_time', 'slot_duration', 'is_available'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Schedule',
            'resource' => 'admin.schedules',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_available'] = $request->boolean('is_available', true);
        Schedule::create($data);

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule created.');
    }

    public function show(Schedule $schedule): View
    {
        $schedule->load('doctor');

        return view('admin.crud.show', [
            'title'    => 'Schedule',
            'resource' => 'admin.schedules',
            'item'     => $schedule,
            'columns'  => ['doctor.name', 'day_of_week', 'start_time', 'end_time', 'slot_duration', 'is_available'],
        ]);
    }

    public function edit(Schedule $schedule): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Schedule',
            'resource' => 'admin.schedules',
            'item'     => $schedule,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_available'] = $request->boolean('is_available');
        $schedule->update($data);

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated.');
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time' => 'required',
            'end_time' => 'required',
            'slot_duration' => 'required|integer|min:5|max:180',
        ]);
    }

    private function fields(): array
    {
        return [
            ['name' => 'doctor_id', 'label' => 'Doctor', 'type' => 'select', 'required' => true, 'options' => User::role('doctor')->orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'day_of_week', 'label' => 'Day of week', 'type' => 'select', 'required' => true, 'options' => Schedule::DAYS],
            ['name' => 'start_time', 'label' => 'Start time', 'type' => 'time', 'required' => true],
            ['name' => 'end_time', 'label' => 'End time', 'type' => 'time', 'required' => true],
            ['name' => 'slot_duration', 'label' => 'Slot duration (minutes)', 'type' => 'number', 'required' => true],
            ['name' => 'is_available', 'label' => 'Available', 'type' => 'checkbox', 'required' => false],
        ];
    }
}
