<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RolesAndPermissionsSeeder::class, // Instantiates core RBAC models first
            HospitalStructureSeeder::class,   // Generates root operational units
            UserAndProfileSeeder::class,       // Connects structured users with role profiles
            ComprehensiveLabTestSeeder::class,
            HospitalOperationsSeeder::class,
        ]);
    }
}
