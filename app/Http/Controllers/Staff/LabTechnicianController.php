<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\LabReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LabTechnicianController extends Controller
{
    public function index(): View
    {
        return view('laboratory.dashboard', [
            'pending' => LabReport::whereIn('status', ['requested', 'sample_collected', 'analyzing'])->count(),
            'completedToday' => LabReport::whereDate('updated_at', today())->where('status', 'completed')->count(),
            'recent' => LabReport::with(['labTest', 'patient'])->latest()->take(8)->get(),
        ]);
    }

    public function paidQueue(): View
    {
        $pendingReports = LabReport::whereIn('status', ['requested', 'sample_collected', 'analyzing'])
            ->where(function ($query) {
                $query->whereDoesntHave('invoice')
                    ->orWhereHas('invoice', fn ($invoice) => $invoice->where('status', 'paid'));
            })
            ->with(['labTest', 'patient', 'invoice'])
            ->latest()
            ->get();

        return view('laboratory.queue', compact('pendingReports'));
    }

    public function collectSample(LabReport $labReport): RedirectResponse
    {
        $this->authorizePaid($labReport);
        $labReport->update([
            'status' => 'sample_collected',
            'technician_id' => auth()->id(),
        ]);

        return back()->with('success', 'Sample marked as collected.');
    }

    public function submitResults(Request $request, LabReport $labReport): RedirectResponse
    {
        $this->authorizePaid($labReport);

        $validated = $request->validate([
            'results_text' => 'required|string',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $labReport->document_path;
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('lab_reports', 'public');
        }

        $labReport->update([
            'results' => ['summary' => $validated['results_text']],
            'document_path' => $path,
            'status' => 'completed',
        ]);

        return back()->with('success', 'Results submitted.');
    }

    public function handoverReport(LabReport $labReport): RedirectResponse
    {
        if ($labReport->status !== 'completed') {
            return back()->with('error', 'Only completed reports can be handed over.');
        }

        if ($labReport->invoice && $labReport->invoice->status !== 'paid') {
            return back()->with('error', 'Invoice is still unpaid.');
        }

        $labReport->update([
            'handed_over_at' => now(),
            'handed_over_by' => auth()->id(),
        ]);

        return back()->with('success', 'Report handed over to the patient.');
    }

    private function authorizePaid(LabReport $labReport): void
    {
        if ($labReport->invoice && $labReport->invoice->status !== 'paid') {
            abort(403, 'Cannot process unpaid laboratory tests.');
        }
    }
}
