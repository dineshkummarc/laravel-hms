<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\LabTest;
use App\Models\Payment;
use App\Models\User;
use App\Services\LabInvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function dashboard(): View
    {
        return view('billing.dashboard', [
            'unpaid' => Invoice::whereIn('status', ['unpaid', 'partial'])->count(),
            'today' => Payment::whereDate('created_at', today())->where('status', 'completed')->sum('amount'),
            'recent' => Invoice::with('patient')->latest()->take(8)->get(),
        ]);
    }

    public function index(): View
    {
        $invoices = Invoice::with('patient')->orderByDesc('created_at')->paginate(20);

        return view('billing.index', compact('invoices'));
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['patient', 'payments']);

        return view('billing.show', compact('invoice'));
    }

    public function storeLabInvoice(Request $request, LabInvoiceService $service): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'lab_test_id' => 'required|exists:lab_tests,id',
        ]);

        $service->createInvoiceForTests(
            patientId: (int) $validated['patient_id'],
            doctorId: null,
            labTestIds: [(int) $validated['lab_test_id']]
        );

        return back()->with('success', 'Lab invoice created.');
    }

    public function createLabInvoiceForm(): View
    {
        return view('billing.lab-invoice', [
            'patients' => User::role('patient')->orderBy('name')->get(),
            'tests' => LabTest::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function processPayment(Request $request, Invoice $invoice): RedirectResponse
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'This invoice is already paid.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:'.$invoice->remaining_balance,
            'payment_method' => 'required|in:cash,card,mobile_banking,insurance',
            'transaction_reference' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($invoice, $validated) {
            Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'transaction_reference' => $validated['transaction_reference'],
                'status' => 'completed',
            ]);

            $paid = $invoice->payments()->where('status', 'completed')->sum('amount');
            $invoice->update([
                'status' => $paid >= $invoice->total ? 'paid' : 'partial',
            ]);
        });

        return redirect()->route('billing.invoices.show', $invoice)->with('success', 'Payment recorded.');
    }
}
