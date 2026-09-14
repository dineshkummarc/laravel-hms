<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\LabInvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Exception;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly LabInvoiceService $invoiceService
    ) {}

    public function index(): View|RedirectResponse
    {
        $cart = Session::get('lab_cart', []);

        if (empty($cart)) {
            return redirect()->route('lab.tests')->with('error', 'Your cart is empty.');
        }

        $total = collect($cart)->sum('cost');

        return view('frontend.checkout.index', compact('cart', 'total'));
    }

    public function process(): RedirectResponse
    {
        $cart = Session::get('lab_cart', []);

        if (empty($cart)) {
            return redirect()->route('lab.tests');
        }

        try {
            $invoice = $this->invoiceService->createInvoiceForTests(
                patientId: auth()->id(),
                doctorId: null,
                labTestIds: collect($cart)->pluck('id')->all()
            );

            Session::forget('lab_cart');

            return redirect()->route('payment.execute', $invoice);
        } catch (Exception $e) {
            return back()->with('error', 'Checkout failed: '.$e->getMessage());
        }
    }

    public function executePayment(Invoice $invoice): View
    {
        abort_unless($invoice->patient_id === auth()->id() || auth()->user()->hasAnyRole(['admin', 'cashier']), 403);

        return view('frontend.checkout.pay', compact('invoice'));
    }

    public function charge(Request $request, Invoice $invoice): RedirectResponse
    {
        abort_unless($invoice->patient_id === auth()->id() || auth()->user()->hasAnyRole(['admin', 'cashier']), 403);

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,card,mobile_banking',
            'transaction_reference' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($invoice, $validated) {
            Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $invoice->remaining_balance,
                'payment_method' => $validated['payment_method'],
                'transaction_reference' => $validated['transaction_reference'] ?? 'WEB-'.strtoupper(uniqid()),
                'status' => 'completed',
            ]);
            $invoice->update(['status' => 'paid']);
        });

        return redirect()->route('payment.success', $invoice);
    }

    public function paymentSuccess(Invoice $invoice): View
    {
        return view('frontend.checkout.result', [
            'invoice' => $invoice,
            'ok' => true,
        ]);
    }

    public function paymentFail(): View
    {
        return view('frontend.checkout.result', [
            'invoice' => null,
            'ok' => false,
        ]);
    }
}
