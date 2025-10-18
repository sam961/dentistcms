<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create super admin user
        User::create([
            'name' => 'Super Admin',
            'email' => 'sam.00961@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_super_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Super Admin user created successfully!');
        $this->command->info('Email: sam.00961@gmail.com');
        $this->command->info('Password: password');
        $this->command->info('Please change the password after first login!');
    }
}
