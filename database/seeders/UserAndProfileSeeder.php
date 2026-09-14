<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use App\Models\PatientProfile;
use App\Models\DoctorProfile;
use App\Models\StaffProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class UserAndProfileSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Fetch initialized departments for foreign key matching
            $pathologyId  = Department::where('name', 'like', '%Pathology%')->first()?->id ?? 1;
            $radiologyId  = Department::where('name', 'like', '%Radiology%')->first()?->id ?? 2;
            $cardiologyId = Department::where('name', 'like', '%Cardiology%')->first()?->id ?? 3;

            /*
            |--------------------------------------------------------------------------
            | 1. Seed Medical Practitioners (Doctors)
            |--------------------------------------------------------------------------
            */
            $doctorsData = [
                [
                    'name'      => 'Dr. Sarah Jenkins',
                    'email'     => 'sarah.j@hospital.local',
                    'specialty' => 'Consultant Pathologist',
                    'dept_id'   => $pathologyId,
                ],
                [
                    'name'      => 'Dr. Alex Mercer',
                    'email'     => 'alex.m@hospital.local',
                    'specialty' => 'Interventional Cardiologist',
                    'dept_id'   => $cardiologyId,
                ],
                [
                    'name'      => 'Dr. Elena Rostova',
                    'email'     => 'elena.r@hospital.local',
                    'specialty' => 'Senior Radiologist',
                    'dept_id'   => $radiologyId,
                ],
            ];

            foreach ($doctorsData as $doc) {
                $user = User::create([
                    'name'         => $doc['name'],
                    'uid'          => 'usr_' . Str::lower(Str::random(12)),
                    'email'        => $doc['email'],
                    'password'     => Hash::make('DoctorSecure2026!'),
                    'biometric_id' => 'BIO-DR-' . rand(1000, 9999),
                    'is_active'    => true,
                ]);
                $user->assignRole('doctor');

                DoctorProfile::create([
                    'user_id'       => $user->id,
                    'department_id' => $doc['dept_id'],
                    'specialty'     => $doc['specialty'],
                    'qualification' => 'MD, FCPS',
                    'room_number'   => 'Chamber-' . rand(101, 199),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 2. Seed Administrative & Technical Allied Staff
            |--------------------------------------------------------------------------
            */
            $staffData = [
                ['name' => 'Alice FrontDesk', 'email' => 'reception@hospital.local', 'role' => 'receptionist', 'title' => 'Front Desk Executive'],
                ['name' => 'Mark Cashier', 'email' => 'billing@hospital.local', 'role' => 'cashier', 'title' => 'Senior Cashier'],
                ['name' => 'John Tech', 'email' => 'lab.tech@hospital.local', 'role' => 'lab_technician', 'title' => 'Pathology Lab Technologist'],
                ['name' => 'Robert Giver', 'email' => 'report.giver@hospital.local', 'role' => 'report_giver', 'title' => 'Medical Report Dispatch Officer'],
                ['name' => 'Nina Ward', 'email' => 'nurse@hospital.local', 'role' => 'nurse', 'title' => 'Charge Nurse'],
                ['name' => 'Priya Pharmacy', 'email' => 'pharmacy@hospital.local', 'role' => 'pharmacist', 'title' => 'Clinical Pharmacist'],
            ];

            foreach ($staffData as $staff) {
                $user = User::create([
                    'name'         => $staff['name'],
                    'uid'          => 'usr_' . Str::lower(Str::random(12)),
                    'email'        => $staff['email'],
                    'password'     => Hash::make('StaffSecure2026!'),
                    'biometric_id' => 'BIO-STF-' . rand(1000, 9999),
                    'is_active'    => true,
                ]);
                $user->assignRole($staff['role']);

                StaffProfile::create([
                    'user_id'      => $user->id,
                    'designation'  => $staff['title'],
                    'shift'        => 'Day',
                    'joining_date' => now()->subYear(),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 3. Seed Standard Patient Accounts
            |--------------------------------------------------------------------------
            */
            $patientsData = [
                ['name' => 'Bruce Wayne', 'email' => 'bruce@waynecorp.com', 'age' => '19', 'gender' => 'male', 'blood' => 'O+'],
                ['name' => 'Diana Prince', 'email' => 'diana@themyscira.org', 'age' => '50', 'gender' => 'female', 'blood' => 'A+'],
                ['name' => 'Clark Kent', 'email' => 'clark@dailyplanet.press', 'age' => '35', 'gender' => 'male', 'blood' => 'AB-'],
            ];

            foreach ($patientsData as $pat) {
                $user = User::create([
                    'name'      => $pat['name'],
                    'uid'       => 'usr_' . Str::lower(Str::random(12)),
                    'email'     => $pat['email'],
                    'password'  => Hash::make('PatientSecure2026!'),
                    'is_active' => true,
                ]);
                $user->assignRole('patient');

                PatientProfile::create([
                    'user_id'           => $user->id,
                    'age'               => $pat['age'],
                    'gender'            => $pat['gender'],
                    'blood_group'       => $pat['blood'],
                    'emergency_contact' => '+1-555-0199',
                    'medical_history' => [
                        'allergies' => ['Penicillin'],
                        'chronic_conditions' => ['None Chronic'],
                        'past_surgeries' => []
                    ],
                ]);
            }
        });
    }
}
