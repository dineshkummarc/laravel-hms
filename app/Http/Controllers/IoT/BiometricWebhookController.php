<?php

namespace App\Http\Controllers\IoT;

use App\Http\Controllers\Controller;
use App\Models\BiometricDevice;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BiometricWebhookController extends Controller
{
    public function handlePunch(Request $request): JsonResponse
    {
        // 1. Authenticate Hardware via API Token (Not user session)
        $deviceToken = $request->header('X-Device-Token');
        $device      = BiometricDevice::where('api_token', $deviceToken)->first();

        if (!$device) {
            return response()->json(['error' => 'Unauthorized device'], 401);
        }

        // 2. Validate Hardware Payload
        $validated = $request->validate([
            'biometric_id'      => 'required|string',
            'punch_time'        => 'required|date',
            'punch_type'        => 'required|in:check_in,check_out',
            'confidence_score'  => 'numeric',
            'verification_mode' => 'string'
        ]);

        // 3. Resolve to actual hospital User
        $user = \App\Models\User::where('biometric_id', $validated['biometric_id'])->first();

        if (!$user) {
            // Log the orphan punch for HR review
            AttendanceLog::create([
                'biometric_device_id' => $device->id,
                'status'              => 'error',
                'error_message'       => 'Unmapped biometric ID: ' . $validated['biometric_id'],
                'raw_payload'         => $request->all(),
            ]);
            return response()->json(['status' => 'error', 'message' => 'User not found in ERP']);
        }

        // 4. Log successful attendance
        AttendanceLog::create([
            'user_id'             => $user->id,
            'biometric_device_id' => $device->id,
            'punch_time'          => $validated['punch_time'],
            'punch_type'          => $validated['punch_type'],
            'verification_mode'   => $validated['verification_mode'] ?? 'unknown',
            'status'              => 'processed',
        ]);

        $device->update(['last_ping_at' => now(), 'is_online' => true]);

        return response()->json(['status' => 'success', 'message' => 'Punch recorded']);
    }

    public function myLogs(): \Illuminate\View\View
    {
        $logs = AttendanceLog::where('user_id', auth()->id())
            ->with('device')
            ->latest('punch_time')
            ->paginate(20);

        return view('staff.attendance', compact('logs'));
    }
}
