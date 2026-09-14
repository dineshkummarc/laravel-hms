<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\BiometricDevice;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceLogCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Attendance logs',
            'resource' => 'admin.attendance-logs',
            'items'    => AttendanceLog::with(['user', 'device'])->latest('punch_time')->paginate(20),
            'columns'  => ['user.name', 'punch_time', 'punch_type', 'status'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Attendance log',
            'resource' => 'admin.attendance-logs',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        AttendanceLog::create($this->payload($request));

        return redirect()->route('admin.attendance-logs.index')->with('success', 'Attendance log created.');
    }

    public function show(AttendanceLog $attendance_log): View
    {
        $attendance_log->load(['user', 'device']);

        return view('admin.crud.show', [
            'title'    => 'Attendance log',
            'resource' => 'admin.attendance-logs',
            'item'     => $attendance_log,
            'columns'  => ['user.name', 'device.name', 'punch_time', 'punch_type', 'verification_mode', 'confidence_score', 'snapshot_path', 'status', 'error_message', 'raw_payload'],
        ]);
    }

    public function edit(AttendanceLog $attendance_log): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Attendance log',
            'resource' => 'admin.attendance-logs',
            'item'     => $attendance_log,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, AttendanceLog $attendance_log): RedirectResponse
    {
        $attendance_log->update($this->payload($request));

        return redirect()->route('admin.attendance-logs.index')->with('success', 'Attendance log updated.');
    }

    public function destroy(AttendanceLog $attendance_log): RedirectResponse
    {
        $attendance_log->delete();

        return redirect()->route('admin.attendance-logs.index')->with('success', 'Attendance log deleted.');
    }

    private function payload(Request $request): array
    {
        $validated = $request->validate([
            'user_id'             => 'required|exists:users,id',
            'biometric_device_id' => 'nullable|exists:biometric_devices,id',
            'punch_time'          => 'required|date',
            'punch_type'          => 'required|in:check_in,check_out,break_start,break_end',
            'verification_mode'   => 'required|in:face,card,fingerprint,pin',
            'confidence_score'    => 'nullable|numeric|min:0|max:100',
            'snapshot_path'       => 'nullable|string|max:255',
            'status'              => 'required|in:pending,processed,error',
            'error_message'       => 'nullable|string',
            'raw_payload'         => 'nullable|string',
        ]);

        $validated['raw_payload'] = $this->decodeJson($request->input('raw_payload'));

        return $validated;
    }

    private function decodeJson(?string $value): array
    {
        if ($value === null || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE && is_array($decoded) ? $decoded : [];
    }

    private function fields(): array
    {
        return [
            ['name' => 'user_id', 'label' => 'User', 'type' => 'select', 'required' => true, 'options' => User::orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'biometric_device_id', 'label' => 'Device', 'type' => 'select', 'required' => false, 'options' => BiometricDevice::orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'punch_time', 'label' => 'Punch time', 'type' => 'datetime-local', 'required' => true],
            ['name' => 'punch_type', 'label' => 'Punch type', 'type' => 'select', 'required' => true, 'options' => ['check_in' => 'Check in', 'check_out' => 'Check out', 'break_start' => 'Break start', 'break_end' => 'Break end']],
            ['name' => 'verification_mode', 'label' => 'Verification mode', 'type' => 'select', 'required' => true, 'options' => ['face' => 'Face', 'card' => 'Card', 'fingerprint' => 'Fingerprint', 'pin' => 'PIN']],
            ['name' => 'confidence_score', 'label' => 'Confidence score', 'type' => 'number', 'required' => false],
            ['name' => 'snapshot_path', 'label' => 'Snapshot path', 'type' => 'text', 'required' => false],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['pending' => 'Pending', 'processed' => 'Processed', 'error' => 'Error']],
            ['name' => 'error_message', 'label' => 'Error message', 'type' => 'textarea', 'required' => false],
            ['name' => 'raw_payload', 'label' => 'Raw payload (JSON)', 'type' => 'textarea', 'required' => false],
        ];
    }
}
