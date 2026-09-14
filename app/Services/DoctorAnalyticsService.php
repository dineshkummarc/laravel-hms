<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DoctorAnalyticsService
{
    public function getDashboardStats(int $doctorId): array
    {
        return [
            // Total unique patients treated by this doctor
            'total_patients' => Appointment::where('doctor_id', $doctorId)
                ->distinct('patient_id')
                ->count('patient_id'),

            // Patients seen for the first time in the last 30 days
            'new_patients' => Appointment::where('doctor_id', $doctorId)
                ->where('created_at', '>=', now()->subDays(30))
                ->distinct('patient_id')
                ->count('patient_id'),

            // Daily load analysis
            'today_appointments' => Appointment::where('doctor_id', $doctorId)
                ->whereDate('appointment_date', today())
                ->count(),

            // Pending lab reviews
            'pending_labs' => DB::table('lab_reports')
                ->where('doctor_id', $doctorId)
                ->where('status', 'completed')
                ->count()
        ];
    }
}
