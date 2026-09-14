<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\LabTest;
use Illuminate\Support\Str; // Ensure this is imported at the top of your seeder file

class ComprehensiveLabTestSeeder extends Seeder
{
    public function run(): void
    {
        $pathology  = Department::where('name', 'like', '%Pathology%')->first();
        $radiology  = Department::where('name', 'like', '%Radiology%')->first();
        $cardiology = Department::where('name', 'like', '%Cardiology%')->first();

        $tests = [];

        // Pathology Test Portfolio Additions
        if ($pathology) {
            $tests[] = [
                'department_id' => $pathology->id,
                'name'          => 'Lipid Profile Panel',
                'cost'          => 1200.00,
                'is_active'     => true
            ];
            $tests[] = [
                'department_id' => $pathology->id,
                'name'          => 'Liver Function Test (LFT)',
                'cost'          => 1500.00,
                'is_active'     => true
            ];
            $tests[] = [
                'department_id' => $pathology->id,
                'name'          => 'Kidney Function Test (KFT / RFT)',
                'cost'          => 1100.00,
                'is_active'     => true
            ];
            $tests[] = [
                'department_id' => $pathology->id,
                'name'          => 'Serum Creatinine Check',
                'cost'          => 350.00,
                'is_active'     => true
            ];
            $tests[] = [
                'department_id' => $pathology->id,
                'name'          => 'Thyroid Panel (T3, T4, TSH)',
                'cost'          => 1350.00,
                'is_active'     => true
            ];
        }

        // Radiology Test Portfolio Additions
        if ($radiology) {
            $tests[] = [
                'department_id' => $radiology->id,
                'name'          => 'Magnetic Resonance Imaging (MRI) - Brain',
                'cost'          => 7500.00,
                'is_active'     => true
            ];
            $tests[] = [
                'department_id' => $radiology->id,
                'name'          => 'Ultrasonography (USG) Whole Abdomen',
                'cost'          => 2200.00,
                'is_active'     => true
            ];
            $tests[] = [
                'department_id' => $radiology->id,
                'name'          => 'Digital Chest X-Ray PA View',
                'cost'          => 500.00,
                'is_active'     => true
            ];
            $tests[] = [
                'department_id' => $radiology->id,
                'name'          => 'CT Scan Paranasal Sinuses (PNS)',
                'cost'          => 3800.00,
                'is_active'     => true
            ];
        }

        // Cardiology Test Portfolio Additions
        if ($cardiology) {
            $tests[] = [
                'department_id' => $cardiology->id,
                'name'          => 'Color Doppler Echocardiogram',
                'cost'          => 3000.00,
                'is_active'     => true
            ];
            $tests[] = [
                'department_id' => $cardiology->id,
                'name'          => 'Exercise Tolerance Test (ETT / TMT)',
                'cost'          => 2500.00,
                'is_active'     => true
            ];
            $tests[] = [
                'department_id' => $cardiology->id,
                'name'          => '24-Hour Holter Monitor Tracking',
                'cost'          => 4000.00,
                'is_active'     => true
            ];
        }


foreach ($tests as $test) {
    LabTest::updateOrCreate(
        [
            'name'          => $test['name'],
            'department_id' => $test['department_id']
        ],
        [
            'slug'      => Str::slug($test['name']), // Generates clean url slug format natively
            'cost'      => $test['cost'],
            'is_active' => $test['is_active']
        ]
    );
}
    }
}
