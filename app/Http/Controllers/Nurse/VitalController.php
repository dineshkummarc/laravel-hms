<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\User;
use App\Models\Vital;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VitalController extends Controller
{
    public function index(): View
    {
        return view('nurse.vitals.index', [
            'vitals' => Vital::with(['patient', 'recorder', 'admission.bed'])->latest('recorded_at')->paginate(20),
            'admissions' => Admission::where('status', 'admitted')->with('patient')->get(),
            'patients' => User::role('patient')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'admission_id' => 'nullable|exists:admissions,id',
            'temperature' => 'nullable|numeric',
            'pulse' => 'nullable|integer',
            'respiratory_rate' => 'nullable|integer',
            'blood_pressure' => 'nullable|string|max:20',
            'spo2' => 'nullable|integer|min:0|max:100',
            'weight_kg' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        Vital::create([
            ...$validated,
            'recorded_by' => auth()->id(),
            'recorded_at' => now(),
        ]);

        return back()->with('success', 'Vitals recorded.');
    }
}
