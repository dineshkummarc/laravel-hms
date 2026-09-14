<?php

namespace Database\Seeders;

use App\Models\BloodUnit;
use App\Models\Medicine;
use App\Models\Notice;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;

class HospitalOperationsSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::role('admin')->value('id');
        $doctors = User::role('doctor')->pluck('id');

        $medicines = [
            ['name' => 'Paracetamol 500mg', 'generic_name' => 'Acetaminophen', 'category' => 'Analgesic', 'unit' => 'tablet', 'unit_price' => 2.50, 'stock_qty' => 400, 'reorder_level' => 50, 'expiry_date' => now()->addMonths(10), 'manufacturer' => 'Square'],
            ['name' => 'Amoxicillin 250mg', 'generic_name' => 'Amoxicillin', 'category' => 'Antibiotic', 'unit' => 'capsule', 'unit_price' => 8.00, 'stock_qty' => 12, 'reorder_level' => 40, 'expiry_date' => now()->addDays(20), 'manufacturer' => 'Beximco'],
            ['name' => 'ORS Sachet', 'generic_name' => 'Oral rehydration salts', 'category' => 'Electrolyte', 'unit' => 'sachet', 'unit_price' => 5.00, 'stock_qty' => 80, 'reorder_level' => 25, 'expiry_date' => now()->addYear(), 'manufacturer' => 'ACI'],
            ['name' => 'Salbutamol Inhaler', 'generic_name' => 'Salbutamol', 'category' => 'Respiratory', 'unit' => 'inhaler', 'unit_price' => 220.00, 'stock_qty' => 18, 'reorder_level' => 10, 'expiry_date' => now()->addMonths(8), 'manufacturer' => 'GSK'],
        ];

        foreach ($medicines as $row) {
            Medicine::updateOrCreate(['name' => $row['name']], $row + ['is_active' => true]);
        }

        $groups = ['A+', 'O+', 'B+', 'AB-'];
        foreach ($groups as $i => $group) {
            BloodUnit::updateOrCreate(
                ['bag_number' => 'BAG-2026-'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'blood_group' => $group,
                    'component' => 'whole_blood',
                    'units' => 1,
                    'donor_name' => 'Walk-in donor '.$group,
                    'collected_on' => now()->subDays(3),
                    'expires_on' => now()->addDays(30),
                    'status' => 'available',
                ]
            );
        }

        Notice::updateOrCreate(
            ['title' => 'OPD hours this week'],
            [
                'body' => 'Outpatient clinics run 9:00–17:00 Saturday–Thursday. Emergency and ambulance remain 24/7.',
                'audience' => 'all',
                'priority' => 'important',
                'is_published' => true,
                'published_at' => now(),
                'created_by' => $adminId,
            ]
        );

        Notice::updateOrCreate(
            ['title' => 'Blood bank O-negative drive'],
            [
                'body' => 'O- stock is limited. Eligible donors may register at reception.',
                'audience' => 'public',
                'priority' => 'urgent',
                'is_published' => true,
                'published_at' => now(),
                'created_by' => $adminId,
            ]
        );

        foreach ($doctors as $doctorId) {
            foreach ([1, 2, 3, 4] as $day) {
                Schedule::updateOrCreate(
                    ['doctor_id' => $doctorId, 'day_of_week' => $day],
                    [
                        'start_time' => '09:00:00',
                        'end_time' => '13:00:00',
                        'slot_duration' => 20,
                        'is_available' => true,
                    ]
                );
            }
        }
    }
}
