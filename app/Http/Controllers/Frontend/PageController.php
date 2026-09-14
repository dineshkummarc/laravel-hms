<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AmbulanceRequest;
use App\Models\Appointment;
use App\Models\ContactMessage;
use App\Models\Department;
use App\Models\Feedback;
use App\Models\LabTest;
use App\Models\Notice;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $featuredTests = LabTest::with('department')->where('is_active', true)->take(4)->get();
        $departments = Department::take(6)->get();
        $testimonials = Feedback::where('is_published', true)->with('patient')->latest()->take(3)->get();
        $notices = Notice::active()->whereIn('audience', ['public', 'all'])->latest()->take(3)->get();

        return view('frontend.pages.home', compact('featuredTests', 'departments', 'testimonials', 'notices'));
    }

    public function about(): View
    {
        return view('frontend.pages.about');
    }

    public function contact(): View
    {
        return view('frontend.pages.contact');
    }

    public function storeContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Message received. Our help desk will reply shortly.');
    }

    public function doctor(): View
    {
        $doctors = User::role('doctor')->with('doctorProfile.department')->paginate(12);

        return view('frontend.pages.doctors', compact('doctors'));
    }

    public function lab(): View
    {
        $labTests = LabTest::with('department')->where('is_active', true)->paginate(15);

        return view('frontend.pages.lab_tests', compact('labTests'));
    }

    public function departments(): View
    {
        $departments = Department::withCount('doctors')->orderBy('name')->get();

        return view('frontend.pages.departments', compact('departments'));
    }

    public function services(): View
    {
        return view('frontend.pages.simple', [
            'title' => 'Clinical services',
            'lead' => 'Outpatient, inpatient, diagnostics, pharmacy, blood bank, and emergency response under one roof.',
            'sections' => [
                'Outpatient clinics' => 'Department-based consultations with tokenized appointment slots and follow-up tracking.',
                'Inpatient & ICU' => 'Ward, cabin, and critical-care beds with live occupancy and nurse vitals rounds.',
                'Laboratory' => 'Paid diagnostic catalog, sample collection, result entry, and report handover.',
                'Pharmacy' => 'Formulary stock, expiry alerts, and prescription dispensing.',
                'Blood bank' => 'Component inventory, donor bags, and emergency cross-match requests.',
                'Ambulance' => '24/7 dispatch with priority tagging from the public site or patient portal.',
            ],
        ]);
    }

    public function notices(): View
    {
        $notices = Notice::active()->whereIn('audience', ['public', 'all'])->latest()->paginate(10);

        return view('frontend.pages.notices', compact('notices'));
    }

    public function ambulance(): View
    {
        return view('frontend.pages.ambulance');
    }

    public function storeAmbulance(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'caller_name' => 'required|string|max:255',
            'caller_phone' => 'required|string|max:30',
            'pickup_address' => 'required|string|max:255',
            'destination' => 'nullable|string|max:255',
            'priority' => 'required|in:standard,urgent,critical',
            'notes' => 'nullable|string',
        ]);

        AmbulanceRequest::create([
            ...$validated,
            'patient_id' => auth()->id(),
            'status' => 'requested',
        ]);

        return back()->with('success', 'Ambulance request submitted. A dispatcher will call the number you provided.');
    }

    public function careers(): View
    {
        return view('frontend.pages.simple', [
            'title' => 'Careers',
            'lead' => 'Join a multidisciplinary hospital team spanning clinical, diagnostic, and operations roles.',
            'sections' => [
                'Clinical' => 'Doctors, nurses, and allied health professionals.',
                'Diagnostics' => 'Lab technicians and report officers.',
                'Operations' => 'Reception, pharmacy, billing, and biomedical engineering.',
            ],
        ]);
    }

    public function insurance(): View
    {
        return view('frontend.pages.simple', [
            'title' => 'Insurance & billing',
            'lead' => 'Cash, card, mobile banking, and insurance payments are recorded against itemized invoices.',
            'sections' => [
                'How billing works' => 'Lab orders, admissions, and pharmacy issues generate invoices with tax and line items.',
                'Patient portal' => 'Registered patients can view unpaid balances and payment history.',
            ],
        ]);
    }

    public function privacy(): View
    {
        return view('frontend.pages.simple', [
            'title' => 'Privacy policy',
            'lead' => 'Clinical records are role-scoped. Patients see only their own appointments, labs, prescriptions, and invoices.',
            'sections' => [
                'Data we store' => 'Identity, clinical notes, diagnostics, billing, attendance, and contact correspondence.',
                'Access control' => 'Spatie roles restrict each portal to the minimum required dataset.',
            ],
        ]);
    }

    public function terms(): View
    {
        return view('frontend.pages.simple', [
            'title' => 'Terms of service',
            'lead' => 'Use of the HMS portals is limited to authorized staff and registered patients.',
            'sections' => [
                'Accounts' => 'Inactive users cannot sign in. Temporary walk-in passwords must be changed at first login.',
                'Emergency care' => 'Public ambulance and ER contact details are provided for life-threatening situations.',
            ],
        ]);
    }

    public function accessibility(): View
    {
        return view('frontend.pages.simple', [
            'title' => 'Accessibility',
            'lead' => 'The public site and portals use high-contrast text, keyboard-accessible forms, and semantic headings.',
            'sections' => [
                'Assistance' => 'Call the front desk if you need help booking, reading reports, or navigating the patient portal.',
            ],
        ]);
    }

    public function antiFraud(): View
    {
        return view('frontend.pages.simple', [
            'title' => 'Anti-fraud policy',
            'lead' => 'Invoice payments require a recorded method and optional transaction reference.',
            'sections' => [
                'Reporting' => 'Suspicious billing or identity issues should be sent through the contact form for admin review.',
            ],
        ]);
    }

    public function appointments(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'required|string|max:20',
        ]);

        $doctor = User::findOrFail($validated['doctor_id']);

        Appointment::create([
            'doctor_id' => $validated['doctor_id'],
            'department_id' => $doctor->doctorProfile?->department_id ?? Department::query()->value('id'),
            'appointment_date' => $validated['appointment_date'],
            'guest_name' => $validated['patient_name'],
            'guest_phone' => $validated['patient_phone'],
            'status' => 'pending',
            'visit_type' => 'initial',
            'symptoms_summary' => 'Guest web booking',
        ]);

        return back()->with('success', 'Your appointment request has been submitted. Reception will confirm by phone.');
    }
}
