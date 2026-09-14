<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Dispense;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PharmacyController extends Controller
{
    public function dashboard(): View
    {
        $medicines = Medicine::orderBy('name')->get();

        return view('pharmacy.dashboard', [
            'lowStock' => $medicines->filter->isLowStock(),
            'expiring' => $medicines->filter(fn (Medicine $m) => $m->expiry_date && $m->expiry_date->lte(now()->addDays(30))),
            'recent' => Dispense::with(['patient', 'medicine'])->latest()->take(10)->get(),
            'pendingPrescriptions' => Prescription::with(['medicalRecord.patient'])->latest()->take(10)->get(),
        ]);
    }

    public function inventory(): View
    {
        return view('pharmacy.inventory', [
            'medicines' => Medicine::orderBy('name')->paginate(25),
        ]);
    }

    public function createDispense(): View
    {
        return view('pharmacy.dispense', [
            'patients' => User::role('patient')->orderBy('name')->get(),
            'medicines' => Medicine::where('is_active', true)->orderBy('name')->get(),
            'prescriptions' => Prescription::with(['medicalRecord.patient'])->latest()->take(50)->get(),
        ]);
    }

    public function storeDispense(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'medicine_id' => 'required|exists:medicines,id',
            'prescription_id' => 'nullable|exists:prescriptions,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $medicine = Medicine::findOrFail($validated['medicine_id']);

        if ($medicine->stock_qty < $validated['quantity']) {
            return back()->with('error', 'Insufficient stock for '.$medicine->name.'.');
        }

        DB::transaction(function () use ($validated, $medicine) {
            $total = $medicine->unit_price * $validated['quantity'];
            Dispense::create([
                ...$validated,
                'dispensed_by' => auth()->id(),
                'unit_price' => $medicine->unit_price,
                'total' => $total,
                'status' => 'dispensed',
            ]);
            $medicine->decrement('stock_qty', $validated['quantity']);
        });

        return redirect()->route('pharmacy.dashboard')->with('success', 'Medicine dispensed and stock updated.');
    }
}
