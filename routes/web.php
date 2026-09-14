<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NoticeBoardController;
use App\Http\Controllers\Patient\PatientDashboardController;
use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\MedicalRecordController;
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\Staff\ReceptionistController;
use App\Http\Controllers\Staff\LabTechnicianController;
use App\Http\Controllers\Staff\BillingController;
use App\Http\Controllers\Staff\PharmacyController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserCRUDController;
use App\Http\Controllers\Admin\DoctorCRUDController;
use App\Http\Controllers\Admin\PatientCRUDController;
use App\Http\Controllers\Admin\StaffCRUDController;
use App\Http\Controllers\Admin\DepartmentCRUDController;
use App\Http\Controllers\Admin\AppointmentCRUDController;
use App\Http\Controllers\Admin\MedicalRecordCRUDController;
use App\Http\Controllers\Admin\PrescriptionCRUDController;
use App\Http\Controllers\Admin\LabTestCRUDController;
use App\Http\Controllers\Admin\LabReportCRUDController;
use App\Http\Controllers\Admin\RoomCRUDController;
use App\Http\Controllers\Admin\BedCRUDController;
use App\Http\Controllers\Admin\AdmissionCRUDController;
use App\Http\Controllers\Admin\InvoiceCRUDController;
use App\Http\Controllers\Admin\PaymentCRUDController;
use App\Http\Controllers\Admin\BiometricDeviceCRUDController;
use App\Http\Controllers\Admin\AttendanceLogCRUDController;
use App\Http\Controllers\Admin\MedicineCRUDController;
use App\Http\Controllers\Admin\BloodUnitCRUDController;
use App\Http\Controllers\Admin\BloodRequestCRUDController;
use App\Http\Controllers\Admin\AmbulanceCRUDController;
use App\Http\Controllers\Admin\NoticeCRUDController;
use App\Http\Controllers\Admin\ContactMessageCRUDController;
use App\Http\Controllers\Admin\FeedbackCRUDController;
use App\Http\Controllers\Admin\ScheduleCRUDController;
use App\Http\Controllers\IoT\BiometricWebhookController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'storeContact'])->name('contact.store');
Route::get('/doctor', [PageController::class, 'doctor'])->name('doctors.index');
Route::get('/lab-tests', [PageController::class, 'lab'])->name('lab.tests');
Route::post('/appointments-store', [PageController::class, 'appointments'])->name('appointments.store');
Route::get('/departments', [PageController::class, 'departments'])->name('departments.public');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/notices', [PageController::class, 'notices'])->name('notices.public');
Route::get('/ambulance', [PageController::class, 'ambulance'])->name('ambulance.create');
Route::post('/ambulance', [PageController::class, 'storeAmbulance'])->name('ambulance.store');
Route::get('/careers', [PageController::class, 'careers'])->name('careers');
Route::get('/insurance', [PageController::class, 'insurance'])->name('insurance');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/accessibility', [PageController::class, 'accessibility'])->name('accessibility');
Route::get('/anti-fraud-policy', [PageController::class, 'antiFraud'])->name('anti-fraud');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{labTest}', [CartController::class, 'add'])->name('add');
    Route::post('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', fn () => view('auth.forgot-password'))->name('password.request');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::post('/webhook/biometric-punch', [BiometricWebhookController::class, 'handlePunch'])
    ->name('webhook.biometric.punch');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', DashboardRedirectController::class)->name('dashboard');
    Route::get('/profile', [LoginController::class, 'profile'])->name('profile.edit');
    Route::put('/profile/update', [LoginController::class, 'updateProfile'])->name('profile.update');
    Route::get('/user/profile', [LoginController::class, 'profile'])->name('user.profile');
    Route::put('/user/profile/update', [LoginController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/my-attendance', [BiometricWebhookController::class, 'myLogs'])->name('user.attendance.logs');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::get('/notice-board', [NoticeBoardController::class, 'index'])->name('notices.board');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/payment/{invoice}/pay', [CheckoutController::class, 'executePayment'])->name('payment.execute');
    Route::post('/payment/{invoice}/charge', [CheckoutController::class, 'charge'])->name('payment.charge');
    Route::get('/payment/{invoice}/success', [CheckoutController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/fail', [CheckoutController::class, 'paymentFail'])->name('payment.fail');

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserCRUDController::class);
        Route::resource('doctors', DoctorCRUDController::class);
        Route::resource('patients', PatientCRUDController::class);
        Route::resource('staff-profiles', StaffCRUDController::class);
        Route::resource('departments', DepartmentCRUDController::class);
        Route::resource('rooms', RoomCRUDController::class);
        Route::resource('beds', BedCRUDController::class);
        Route::resource('appointments', AppointmentCRUDController::class);
        Route::resource('medical-records', MedicalRecordCRUDController::class);
        Route::resource('prescriptions', PrescriptionCRUDController::class);
        Route::resource('lab-tests', LabTestCRUDController::class);
        Route::resource('lab-reports', LabReportCRUDController::class);
        Route::resource('admissions', AdmissionCRUDController::class);
        Route::resource('invoices', InvoiceCRUDController::class);
        Route::resource('payments', PaymentCRUDController::class);
        Route::resource('biometric-devices', BiometricDeviceCRUDController::class);
        Route::resource('attendance-logs', AttendanceLogCRUDController::class);
        Route::resource('medicines', MedicineCRUDController::class);
        Route::resource('blood-units', BloodUnitCRUDController::class);
        Route::resource('blood-requests', BloodRequestCRUDController::class);
        Route::post('blood-requests/{blood_request}/fulfill', [BloodRequestCRUDController::class, 'fulfill'])->name('blood-requests.fulfill');
        Route::resource('ambulance-requests', AmbulanceCRUDController::class);
        Route::resource('notices', NoticeCRUDController::class);
        Route::resource('contact-messages', ContactMessageCRUDController::class);
        Route::resource('feedback', FeedbackCRUDController::class);
        Route::resource('schedules', ScheduleCRUDController::class);
    });

    Route::middleware(['role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/appointments', [DoctorDashboardController::class, 'appointments'])->name('appointments.index');
        Route::get('/appointments/{appointment}', [DoctorDashboardController::class, 'showAppointment'])->name('appointments.show');
        Route::get('/appointments/{appointment}/follow-up', [DoctorDashboardController::class, 'showFollowUpContext'])->name('appointments.followup');
        Route::post('/appointments/{appointment}/medical-records', [MedicalRecordController::class, 'store'])->name('records.store');
        Route::post('/medical-records/{medicalRecord}/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
        Route::get('/patients/{user}/historical-labs', [DoctorDashboardController::class, 'patientLabHistory'])->name('patients.labs.history');
        Route::get('/schedules', [DoctorDashboardController::class, 'schedules'])->name('schedules.index');
    });

    Route::middleware(['role:receptionist|admin'])->prefix('receptionist')->name('receptionist.')->group(function () {
        Route::get('/dashboard', [ReceptionistController::class, 'index'])->name('dashboard');
        Route::post('/patients/quick-reg', [ReceptionistController::class, 'quickRegisterPatient'])->name('patients.quick-reg');
        Route::get('/patients/search', [ReceptionistController::class, 'searchPatients'])->name('patients.search');
        Route::get('/doctors/{doctor}/available-slots', [ReceptionistController::class, 'getAvailableSlots'])->name('doctors.slots');
        Route::post('/appointments/book', [ReceptionistController::class, 'bookAppointment'])->name('appointments.book');
        Route::patch('/appointments/{appointment}/status', [ReceptionistController::class, 'updateStatus'])->name('appointments.status');
        Route::get('/beds/occupancy-map', [ReceptionistController::class, 'bedMap'])->name('beds.map');
        Route::post('/admissions/admit', [ReceptionistController::class, 'admitPatient'])->name('admissions.admit');
        Route::post('/admissions/{admission}/discharge', [ReceptionistController::class, 'dischargePatient'])->name('admissions.discharge');
        Route::get('/ambulance', [ReceptionistController::class, 'ambulanceIndex'])->name('ambulance.index');
        Route::post('/ambulance/{ambulanceRequest}', [ReceptionistController::class, 'dispatchAmbulance'])->name('ambulance.dispatch');
    });

    Route::middleware(['role:cashier|admin'])->prefix('billing')->name('billing.')->group(function () {
        Route::get('/dashboard', [BillingController::class, 'dashboard'])->name('dashboard');
        Route::get('/lab-invoice', [BillingController::class, 'createLabInvoiceForm'])->name('labs.invoice.form');
        Route::post('/lab-invoices/create', [BillingController::class, 'storeLabInvoice'])->name('labs.invoice.create');
        Route::get('/invoices', [BillingController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{invoice}', [BillingController::class, 'show'])->name('invoices.show');
        Route::post('/invoices/{invoice}/payments', [BillingController::class, 'processPayment'])->name('invoices.payments.process');
    });

    Route::middleware(['role:lab_technician|report_giver|admin'])->prefix('laboratory')->name('laboratory.')->group(function () {
        Route::get('/dashboard', [LabTechnicianController::class, 'index'])->name('dashboard');
        Route::get('/queue/pending', [LabTechnicianController::class, 'paidQueue'])->name('queue.pending');
        Route::patch('/reports/{labReport}/collect-sample', [LabTechnicianController::class, 'collectSample'])->name('reports.sample');
        Route::put('/reports/{labReport}/submit-results', [LabTechnicianController::class, 'submitResults'])->name('reports.submit');
        Route::patch('/reports/{labReport}/handover', [LabTechnicianController::class, 'handoverReport'])->name('reports.handover');
    });

    Route::middleware(['role:pharmacist|admin'])->prefix('pharmacy')->name('pharmacy.')->group(function () {
        Route::get('/dashboard', [PharmacyController::class, 'dashboard'])->name('dashboard');
        Route::get('/inventory', [PharmacyController::class, 'inventory'])->name('inventory');
        Route::get('/dispense', [PharmacyController::class, 'createDispense'])->name('dispense.create');
        Route::post('/dispense', [PharmacyController::class, 'storeDispense'])->name('dispense.store');
    });

    Route::middleware(['role:patient'])->prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
        Route::get('/book-appointment', [PatientDashboardController::class, 'showBookingForm'])->name('appointments.book.form');
        Route::post('/book-appointment', [PatientDashboardController::class, 'storeAppointment'])->name('appointments.book.store');
        Route::get('/my-appointments', [PatientDashboardController::class, 'myAppointments'])->name('appointments.index');
        Route::patch('/my-appointments/{appointment}/cancel', [PatientDashboardController::class, 'cancelAppointment'])->name('appointments.cancel');
        Route::get('/buy-lab-test', [PatientDashboardController::class, 'showLabPurchaseForm'])->name('labs.buy.form');
        Route::post('/buy-lab-test', [PatientDashboardController::class, 'storeLabPurchase'])->name('labs.buy.store');
        Route::get('/my-lab-reports', [PatientDashboardController::class, 'myLabReports'])->name('labs');
        Route::get('/my-lab-reports/{labReport}/download', [PatientDashboardController::class, 'downloadPDF'])->name('labs.download');
        Route::get('/my-prescriptions', [PatientDashboardController::class, 'myPrescriptions'])->name('prescriptions.index');
        Route::get('/my-invoices', [PatientDashboardController::class, 'myInvoices'])->name('invoices.index');
        Route::get('/medical-records', [PatientDashboardController::class, 'medicalRecords'])->name('records.index');
        Route::get('/feedback', [PatientDashboardController::class, 'feedbackForm'])->name('feedback.index');
        Route::post('/feedback', [PatientDashboardController::class, 'storeFeedback'])->name('feedback.store');
        Route::get('/ambulance', [PatientDashboardController::class, 'ambulanceForm'])->name('ambulance.index');
        Route::post('/ambulance', [PatientDashboardController::class, 'storeAmbulance'])->name('ambulance.store');
    });

    Route::middleware(['role:nurse|admin'])->prefix('nurse')->name('nurse.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Nurse\NurseDashboardController::class, 'index'])->name('dashboard');
        Route::get('/wards/status', [\App\Http\Controllers\Nurse\WardController::class, 'index'])->name('wards.index');
        Route::get('/vitals', [\App\Http\Controllers\Nurse\VitalController::class, 'index'])->name('vitals.index');
        Route::post('/vitals', [\App\Http\Controllers\Nurse\VitalController::class, 'store'])->name('vitals.store');
    });
});
