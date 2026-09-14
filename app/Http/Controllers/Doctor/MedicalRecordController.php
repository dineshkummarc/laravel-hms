<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    public function store(Request $request, Appointment $appointment): RedirectResponse
    {
        abort_if($appointment->doctor_id !== auth()->id(), 403, 'Unauthorized clinical assignment access.');

        $validated = $request->validate([
            'clinical_notes' => 'required|string',
            'diagnosis'      => 'required|string',
            'vitals'         => 'required|array',
            'vitals.bp'      => 'required|string',      // e.g., "120/80"
            'vitals.pulse'   => 'required|integer',  // e.g., 72
            'vitals.temp'    => 'required|numeric',   // e.g., 98.6
        ]);

        DB::transaction(function () use ($appointment, $validated) {
            // 1. Log Medical Assessment
            $record = MedicalRecord::create([
                'appointment_id' => $appointment->id,
                'patient_id'     => $appointment->patient_id,
                'doctor_id'      => $appointment->doctor_id,
                'clinical_notes' => $validated['clinical_notes'],
                'diagnosis'      => $validated['diagnosis'],
                'vitals'         => $validated['vitals'],
            ]);

            // 2. Advance Appointment Workflow Status State
            $appointment->update(['status' => 'completed']);
        });

        return redirect()->route('doctor.appointments.index')
            ->with('success', 'Medical assessment recorded and appointment closed.');
    }
}
