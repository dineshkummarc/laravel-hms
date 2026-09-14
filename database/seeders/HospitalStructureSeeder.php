<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\LabTest;
use App\Models\Room;
use App\Models\Bed;
use Illuminate\Support\Str;

class HospitalStructureSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Define Comprehensive Department Matrix
        $departments = [
            'Pathology & Molecular Diagnostics',
            'Radiology & Imaging',
            'Cardiology Division',
            'Neurology & Neurosurgery',
            'Orthopedics & Traumatology',
            'General Surgery',
            'Pediatrics & Neonatology',
            'Emergency & Critical Care'
        ];

        foreach ($departments as $deptName) {
            Department::updateOrCreate(
                ['name' => $deptName],
                ['slug' => Str::slug($deptName)]
            );
        }

        // Fetch departments for relational binding
        $pathology  = Department::where('slug', 'pathology-molecular-diagnostics')->first();
        $radiology  = Department::where('slug', 'radiology-imaging')->first();
        $cardiology = Department::where('slug', 'cardiology-division')->first();

        // 2. Seed Lab Tests with Slugs
        $tests = [
            ['dept' => $pathology, 'name' => 'Complete Blood Count (CBC) with ESR', 'cost' => 450.00],
            ['dept' => $pathology, 'name' => 'HbA1c (Glycated Haemoglobin)', 'cost' => 650.00],
            ['dept' => $radiology, 'name' => 'High-Resolution Chest CT Scan', 'cost' => 4500.00],
            ['dept' => $cardiology, 'name' => '12-Lead Electrocardiogram (ECG)', 'cost' => 800.00],
        ];

        foreach ($tests as $test) {
            LabTest::updateOrCreate(
                ['name' => $test['name']],
                [
                    'department_id' => $test['dept']->id,
                    'slug'          => Str::slug($test['name']),
                    'cost'          => $test['cost'],
                    'is_active'     => true
                ]
            );
        }

        // 3. Seed Rooms and Bed Allocations
        $rooms = [
            ['number' => 'WARD-GEN-101', 'dept' => $pathology, 'type' => 'general_ward'],
            ['number' => 'ICU-CRIT-202', 'dept' => $radiology, 'type' => 'icu'],
            ['number' => 'CAB-CAR-303',  'dept' => $cardiology, 'type' => 'private_cabin'],
        ];

        foreach ($rooms as $roomData) {
            $room = Room::updateOrCreate(
                ['room_number' => $roomData['number']],
                [
                    'department_id' => $roomData['dept']->id,
                ]
            );

            // Seed 5 beds per room
            for ($i = 1; $i <= 5; $i++) {
                Bed::updateOrCreate(
                    ['room_id' => $room->id, 'bed_number' => 'B-' . $i],
                    ['status'  => 'available']
                );
            }
        }
    }
}
