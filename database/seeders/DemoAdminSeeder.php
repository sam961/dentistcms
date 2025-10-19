<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoAdminSeeder extends Seeder
{
    // Static credentials for demo account
    public const DEMO_EMAIL = 'demo@dentistcms.com';

    public const DEMO_PASSWORD = 'demo123456';

    public const DEMO_2FA_CODE = '123456';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🎭 Creating demo admin account...');

        // Create or find demo tenant
        $demoTenant = Tenant::firstOrCreate(
            ['subdomain' => 'demo'],
            [
                'name' => 'Demo Dental Clinic',
                'email' => self::DEMO_EMAIL,
                'phone' => '+1234567890',
                'address' => '123 Demo Street, Demo City, Demo State',
                'status' => 'active',
                'subscription_status' => 'active',
                'subscription_tier' => 'professional',
                'subscription_starts_at' => now(),
                'subscription_ends_at' => now()->addYear(),
                'next_billing_date' => now()->addMonth(),
                'auto_renew' => true,
            ]
        );

        // Create or update demo admin user
        $demoUser = User::updateOrCreate(
            ['email' => self::DEMO_EMAIL],
            [
                'tenant_id' => $demoTenant->id,
                'name' => 'Demo Admin',
                'password' => Hash::make(self::DEMO_PASSWORD),
                'role' => 'admin',
                'is_super_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        // Create static 2FA verification code (never expires for demo)
        VerificationCode::updateOrCreate(
            [
                'user_id' => $demoUser->id,
                'type' => VerificationCode::TYPE_LOGIN_2FA,
                'code' => self::DEMO_2FA_CODE,
            ],
            [
                'expires_at' => now()->addYears(10), // Effectively never expires
                'is_used' => false,
            ]
        );

        $this->command->info('✅ Demo admin account created successfully!');
        $this->command->newLine();
        $this->command->line('📧 Email: '.self::DEMO_EMAIL);
        $this->command->line('🔑 Password: '.self::DEMO_PASSWORD);
        $this->command->line('🔐 2FA Code: '.self::DEMO_2FA_CODE);
        $this->command->line('🌐 Subdomain: demo.'.config('app.domain', 'dentistcms.test'));
        $this->command->newLine();
    }
}
