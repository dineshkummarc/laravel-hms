<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\AmbulanceRequest;
use App\Models\Appointment;
use App\Models\BloodRequest;
use App\Models\ContactMessage;
use App\Models\Invoice;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        return view('portal.dashboard', [
            'heading' => 'Hospital operations',
            'subtitle' => 'Live census, revenue, and open clinical queues.',
            'stats' => [
                ['label' => 'Doctors', 'value' => User::role('doctor')->count()],
                ['label' => 'Patients', 'value' => User::role('patient')->count()],
                ['label' => 'Staff', 'value' => User::role(['receptionist', 'lab_technician', 'cashier', 'nurse', 'pharmacist', 'report_giver'])->count()],
                ['label' => 'Open appointments', 'value' => Appointment::whereIn('status', ['pending', 'confirmed'])->count()],
                ['label' => 'Admitted now', 'value' => Admission::where('status', 'admitted')->count()],
                ['label' => 'Unpaid invoices', 'value' => Invoice::where('status', 'unpaid')->count()],
                ['label' => 'Ambulance queue', 'value' => AmbulanceRequest::whereIn('status', ['requested', 'dispatched', 'en_route'])->count()],
                ['label' => 'Blood requests', 'value' => BloodRequest::where('status', 'pending')->count()],
                ['label' => 'Low-stock medicines', 'value' => Medicine::get()->filter->isLowStock()->count()],
                ['label' => 'New contact messages', 'value' => ContactMessage::where('status', 'new')->count()],
                ['label' => 'Paid revenue', 'value' => number_format((float) Invoice::where('status', 'paid')->sum('total'), 2)],
                ['label' => 'Outstanding', 'value' => number_format((float) Invoice::whereIn('status', ['unpaid', 'partial'])->sum('total'), 2)],
            ],
            'recent' => Appointment::with(['patient', 'doctor'])->latest()->take(8)->get(),
        ]);
    }
}
