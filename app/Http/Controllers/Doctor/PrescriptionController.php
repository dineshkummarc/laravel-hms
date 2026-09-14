<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PrescriptionController extends Controller
{
    public function store(Request $request, MedicalRecord $medicalRecord): RedirectResponse
    {
        abort_if($medicalRecord->doctor_id !== auth()->id(), 403, 'Unauthorized context modification.');

        $validated = $request->validate([
            'medications'             => 'required|array|min:1',
            'medications.*.drug'      => 'nullable|string',
            'medications.*.dosage'    => 'nullable|string',
            'medications.*.frequency' => 'nullable|string',
            'medications.*.duration'  => 'nullable|string',
            'instructions'            => 'nullable|string',
        ]);

        $medications = collect($validated['medications'])
            ->filter(fn ($row) => filled($row['drug'] ?? null))
            ->values()
            ->all();

        if ($medications === []) {
            return back()->withErrors(['medications' => 'Add at least one medicine.'])->withInput();
        }

        Prescription::create([
            'medical_record_id' => $medicalRecord->id,
            'medications'       => $medications,
            'instructions'      => $validated['instructions'] ?? null,
        ]);

        return redirect()->route('doctor.dashboard')
            ->with('success', 'Structured prescription attached to medical history layout successfully.');
    }
}
