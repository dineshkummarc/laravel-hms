<?php

namespace App\Support;

use App\Models\Message;
use App\Models\User;

class Portal
{
    public static function homeUrl(?User $user = null): string
    {
        $user ??= auth()->user();

        if (! $user) {
            return route('login');
        }

        return match (true) {
            $user->hasRole('admin') => route('admin.dashboard'),
            $user->hasRole('doctor') => route('doctor.dashboard'),
            $user->hasRole('nurse') => route('nurse.dashboard'),
            $user->hasRole('receptionist') => route('receptionist.dashboard'),
            $user->hasRole('cashier') => route('billing.dashboard'),
            $user->hasAnyRole(['lab_technician', 'report_giver']) => route('laboratory.dashboard'),
            $user->hasRole('pharmacist') => route('pharmacy.dashboard'),
            $user->hasRole('patient') => route('patient.dashboard'),
            default => route('home'),
        };
    }

    public static function unreadCount(?User $user = null): int
    {
        $user ??= auth()->user();

        if (! $user) {
            return 0;
        }

        return Message::where('receiver_id', $user->id)->whereNull('read_at')->count();
    }

    public static function nav(User $user): array
    {
        if ($user->hasRole('admin')) {
            return [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard'],
                ['label' => 'Users', 'route' => 'admin.users.index', 'pattern' => 'admin.users.*'],
                ['label' => 'Doctors', 'route' => 'admin.doctors.index', 'pattern' => 'admin.doctors.*'],
                ['label' => 'Patients', 'route' => 'admin.patients.index', 'pattern' => 'admin.patients.*'],
                ['label' => 'Staff', 'route' => 'admin.staff-profiles.index', 'pattern' => 'admin.staff-profiles.*'],
                ['label' => 'Departments', 'route' => 'admin.departments.index', 'pattern' => 'admin.departments.*'],
                ['label' => 'Rooms', 'route' => 'admin.rooms.index', 'pattern' => 'admin.rooms.*'],
                ['label' => 'Beds', 'route' => 'admin.beds.index', 'pattern' => 'admin.beds.*'],
                ['label' => 'Appointments', 'route' => 'admin.appointments.index', 'pattern' => 'admin.appointments.*'],
                ['label' => 'Schedules', 'route' => 'admin.schedules.index', 'pattern' => 'admin.schedules.*'],
                ['label' => 'Medical records', 'route' => 'admin.medical-records.index', 'pattern' => 'admin.medical-records.*'],
                ['label' => 'Prescriptions', 'route' => 'admin.prescriptions.index', 'pattern' => 'admin.prescriptions.*'],
                ['label' => 'Lab tests', 'route' => 'admin.lab-tests.index', 'pattern' => 'admin.lab-tests.*'],
                ['label' => 'Lab reports', 'route' => 'admin.lab-reports.index', 'pattern' => 'admin.lab-reports.*'],
                ['label' => 'Admissions', 'route' => 'admin.admissions.index', 'pattern' => 'admin.admissions.*'],
                ['label' => 'Invoices', 'route' => 'admin.invoices.index', 'pattern' => 'admin.invoices.*'],
                ['label' => 'Payments', 'route' => 'admin.payments.index', 'pattern' => 'admin.payments.*'],
                ['label' => 'Medicines', 'route' => 'admin.medicines.index', 'pattern' => 'admin.medicines.*'],
                ['label' => 'Blood bank', 'route' => 'admin.blood-units.index', 'pattern' => 'admin.blood-units.*'],
                ['label' => 'Blood requests', 'route' => 'admin.blood-requests.index', 'pattern' => 'admin.blood-requests.*'],
                ['label' => 'Ambulance', 'route' => 'admin.ambulance-requests.index', 'pattern' => 'admin.ambulance-requests.*'],
                ['label' => 'Notices', 'route' => 'admin.notices.index', 'pattern' => 'admin.notices.*'],
                ['label' => 'Contact inbox', 'route' => 'admin.contact-messages.index', 'pattern' => 'admin.contact-messages.*'],
                ['label' => 'Feedback', 'route' => 'admin.feedback.index', 'pattern' => 'admin.feedback.*'],
                ['label' => 'Devices', 'route' => 'admin.biometric-devices.index', 'pattern' => 'admin.biometric-devices.*'],
                ['label' => 'Attendance', 'route' => 'admin.attendance-logs.index', 'pattern' => 'admin.attendance-logs.*'],
                ['label' => 'Messages', 'route' => 'messages.index', 'pattern' => 'messages.*'],
            ];
        }

        if ($user->hasRole('doctor')) {
            return [
                ['label' => 'Dashboard', 'route' => 'doctor.dashboard', 'pattern' => 'doctor.dashboard'],
                ['label' => 'Appointments', 'route' => 'doctor.appointments.index', 'pattern' => 'doctor.appointments.*'],
                ['label' => 'My schedule', 'route' => 'doctor.schedules.index', 'pattern' => 'doctor.schedules.*'],
                ['label' => 'Messages', 'route' => 'messages.index', 'pattern' => 'messages.*'],
                ['label' => 'Notices', 'route' => 'notices.board', 'pattern' => 'notices.board'],
            ];
        }

        if ($user->hasRole('nurse')) {
            return [
                ['label' => 'Dashboard', 'route' => 'nurse.dashboard', 'pattern' => 'nurse.dashboard'],
                ['label' => 'Wards', 'route' => 'nurse.wards.index', 'pattern' => 'nurse.wards.*'],
                ['label' => 'Vitals', 'route' => 'nurse.vitals.index', 'pattern' => 'nurse.vitals.*'],
                ['label' => 'Messages', 'route' => 'messages.index', 'pattern' => 'messages.*'],
                ['label' => 'Notices', 'route' => 'notices.board', 'pattern' => 'notices.board'],
            ];
        }

        if ($user->hasRole('receptionist')) {
            return [
                ['label' => 'Front desk', 'route' => 'receptionist.dashboard', 'pattern' => 'receptionist.dashboard'],
                ['label' => 'Bed map', 'route' => 'receptionist.beds.map', 'pattern' => 'receptionist.beds.*'],
                ['label' => 'Ambulance', 'route' => 'receptionist.ambulance.index', 'pattern' => 'receptionist.ambulance.*'],
                ['label' => 'Messages', 'route' => 'messages.index', 'pattern' => 'messages.*'],
                ['label' => 'Notices', 'route' => 'notices.board', 'pattern' => 'notices.board'],
            ];
        }

        if ($user->hasRole('cashier')) {
            return [
                ['label' => 'Billing', 'route' => 'billing.dashboard', 'pattern' => 'billing.dashboard'],
                ['label' => 'Invoices', 'route' => 'billing.invoices.index', 'pattern' => 'billing.invoices.*'],
                ['label' => 'Messages', 'route' => 'messages.index', 'pattern' => 'messages.*'],
            ];
        }

        if ($user->hasAnyRole(['lab_technician', 'report_giver'])) {
            return [
                ['label' => 'Laboratory', 'route' => 'laboratory.dashboard', 'pattern' => 'laboratory.dashboard'],
                ['label' => 'Pending queue', 'route' => 'laboratory.queue.pending', 'pattern' => 'laboratory.queue.*'],
                ['label' => 'Messages', 'route' => 'messages.index', 'pattern' => 'messages.*'],
            ];
        }

        if ($user->hasRole('pharmacist')) {
            return [
                ['label' => 'Pharmacy', 'route' => 'pharmacy.dashboard', 'pattern' => 'pharmacy.dashboard'],
                ['label' => 'Inventory', 'route' => 'pharmacy.inventory', 'pattern' => 'pharmacy.inventory'],
                ['label' => 'Dispense', 'route' => 'pharmacy.dispense.create', 'pattern' => 'pharmacy.dispense.*'],
                ['label' => 'Messages', 'route' => 'messages.index', 'pattern' => 'messages.*'],
            ];
        }

        return [
            ['label' => 'Dashboard', 'route' => 'home', 'pattern' => 'home'],
        ];
    }
}
