<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // REQUIRED: Import for uid generation

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Permissions using firstOrCreate
        $permissions = [
            'manage_users',
            'manage_infrastructure',
            'write_clinical_records',
            'issue_prescriptions',
            'access_reception_desk',
            'allocate_beds',
            'collect_payments',
            'process_samples',
            'handover_diagnostic_reports'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Define Roles and Assign Permissions using firstOrCreate
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        Role::firstOrCreate(['name' => 'doctor'])->syncPermissions([
            'write_clinical_records',
            'issue_prescriptions'
        ]);

        Role::firstOrCreate(['name' => 'receptionist'])->syncPermissions([
            'access_reception_desk',
            'allocate_beds'
        ]);

        Role::firstOrCreate(['name' => 'cashier'])->syncPermissions([
            'collect_payments'
        ]);

        Role::firstOrCreate(['name' => 'lab_technician'])->syncPermissions([
            'process_samples'
        ]);

        Role::firstOrCreate(['name' => 'report_giver'])->syncPermissions([
            'handover_diagnostic_reports'
        ]);

        // Add this to your roles section
        Role::firstOrCreate(['name' => 'nurse'])->syncPermissions([
            'write_clinical_records', // To update patient vitals/records
            'allocate_beds'           // To help with ward management
        ]);

        Role::firstOrCreate(['name' => 'pharmacist'])->syncPermissions([
            'collect_payments',
        ]);

        Role::firstOrCreate(['name' => 'patient']);

        // 3. Seed Root System Administrator Account
        // Using updateOrCreate ensures we don't create duplicate admin users if run multiple times
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@hospital.local'],
            [
                'name'      => 'Executive System Admin',
                'uid'       => 'usr_' . Str::lower(Str::random(12)),
                'password'  => Hash::make('ProductionSecurePass2026!'),
                'is_active' => true,
            ]
        );

        $adminUser->assignRole('admin');
    }
}
