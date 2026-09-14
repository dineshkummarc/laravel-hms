<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\LabReport;
use App\Models\LabTest;
use Illuminate\Support\Facades\DB;
use Exception;

class LabInvoiceService
{
    /**
     * Generates a paid invoice and queues lab reports for the technicians.
     */
    public function createInvoiceForTests(int $patientId, ?int $doctorId, array $labTestIds): Invoice
    {
        return DB::transaction(function () use ($patientId, $doctorId, $labTestIds) {

            $tests = LabTest::whereIn('id', $labTestIds)->get();

            if ($tests->isEmpty()) {
                throw new Exception("No valid tests found.");
            }

            $doctorId = $doctorId ?? \App\Models\User::role('doctor')->value('id');

            $subtotal = $tests->sum('cost');
            $tax      = $subtotal * 0.05; // 5% standard tax
            $total    = $subtotal + $tax;

            // 1. Create the Financial Invoice
            $invoice = Invoice::create([
                'patient_id'    => $patientId,
                'subtotal'      => $subtotal,
                'tax'           => $tax,
                'discount'      => 0,
                'total'         => $total,
                'status'        => 'unpaid', // Will be updated to paid by the payment gateway
                'line_items'    => $tests->map(fn($test) => [
                    'test_name' => $test->name,
                    'cost'      => $test->cost
                ])->toArray(),
            ]);

            // 2. Queue the Lab Reports for the Medical Hardware/Technicians
            foreach ($tests as $test) {
                LabReport::create([
                    'patient_id'  => $patientId,
                    'doctor_id'   => $doctorId,
                    'lab_test_id' => $test->id,
                    'invoice_id'  => $invoice->id,
                    'status'      => 'requested',
                    'results'     => null,
                ]);
            }

            return $invoice;
        });
    }
}
