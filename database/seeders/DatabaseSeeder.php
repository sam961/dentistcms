<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create super admin user
        User::create([
            'name' => 'Super Admin',
            'email' => 'sam.00961@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'is_super_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@dentistcms.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create receptionist user
        User::create([
            'name' => 'Receptionist',
            'email' => 'receptionist@dentistcms.com',
            'password' => bcrypt('password'),
            'role' => 'receptionist',
        ]);

        // Run other seeders
        $this->call([
            TreatmentSeeder::class,
            DentistSeeder::class,
            PatientSeeder::class,
            AppointmentSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}
