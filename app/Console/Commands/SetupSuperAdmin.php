<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SetupSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:superadmin {--email=} {--password=} {--name=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a super admin user for cloud deployment';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔐 Super Admin Setup');
        $this->newLine();

        // Get credentials
        $email = $this->option('email') ?: $this->ask('Super Admin Email');
        $name = $this->option('name') ?: $this->ask('Super Admin Name', 'Super Admin');
        $password = $this->option('password') ?: $this->secret('Super Admin Password (min 8 characters)');

        // Validate email
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $this->error('Invalid email address!');

            return self::FAILURE;
        }

        // Validate password
        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters!');

            return self::FAILURE;
        }

        // Check if super admin already exists
        $existingSuperAdmin = User::where('is_super_admin', true)->first();

        if ($existingSuperAdmin) {
            $this->warn('⚠️  A super admin already exists: '.$existingSuperAdmin->email);

            if (! $this->confirm('Do you want to create another super admin?', false)) {
                $this->info('Setup cancelled.');

                return self::SUCCESS;
            }
        }

        // Check if email already exists
        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            $this->error('❌ A user with this email already exists!');

            return self::FAILURE;
        }

        // Create super admin
        $superAdmin = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_super_admin' => true,
            'email_verified_at' => now(),
            'tenant_id' => null,
            'role' => 'admin',
        ]);

        $this->newLine();
        $this->info('✅ Super Admin created successfully!');
        $this->newLine();
        $this->line('📧 Email: '.$superAdmin->email);
        $this->line('👤 Name: '.$superAdmin->name);
        $this->line('🔑 Password: ********');
        $this->newLine();
        $this->info('🌐 Login URL: '.url('/admin/login'));
        $this->newLine();

        return self::SUCCESS;
    }
}
