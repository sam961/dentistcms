<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Database\Seeders\DemoAdminSeeder;
use Illuminate\Console\Command;

class SetupDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:demo {--fresh : Delete existing demo data first}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup demo account with seeded data (auto-resets every hour)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🎭 Demo Account Setup');
        $this->newLine();

        // Check if demo already exists
        $existingDemo = Tenant::where('subdomain', 'demo')->first();

        if ($existingDemo && ! $this->option('fresh')) {
            $this->warn('⚠️  Demo account already exists!');

            if (! $this->confirm('Do you want to recreate it with fresh data?', false)) {
                $this->info('Setup cancelled. Use --fresh flag to force recreation.');

                return self::SUCCESS;
            }
        }

        // Delete existing demo if fresh flag is used or confirmed
        if ($existingDemo && ($this->option('fresh') || $this->confirm('Delete existing demo?', false))) {
            $this->warn('🗑️  Deleting existing demo tenant...');
            $existingDemo->delete();
            $this->info('✅ Existing demo deleted.');
        }

        $this->newLine();
        $this->info('📦 Creating demo tenant and admin user...');

        // Run DemoAdminSeeder
        $this->call('db:seed', [
            '--class' => 'Database\\Seeders\\DemoAdminSeeder',
            '--force' => true,
        ]);

        $this->newLine();
        $this->info('🌱 Seeding demo data...');

        // Run TenantDataSeeder
        $this->call('db:seed', [
            '--class' => 'Database\\Seeders\\TenantDataSeeder',
            '--force' => true,
        ]);

        $this->newLine();
        $this->info('✅ Demo account setup complete!');
        $this->newLine();

        // Display credentials
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->line('  📧 Email: '.DemoAdminSeeder::DEMO_EMAIL);
        $this->line('  🔑 Password: '.DemoAdminSeeder::DEMO_PASSWORD);
        $this->line('  🔐 2FA Code: '.DemoAdminSeeder::DEMO_2FA_CODE);
        $this->line('  🌐 URL: demo.'.config('app.domain', 'yourdomain.com'));
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->newLine();

        $this->info('ℹ️  Demo data will reset automatically every hour.');
        $this->line('   Manual reset: php artisan demo:reset --force');
        $this->newLine();

        $this->warn('⚠️  Make sure to add cron job for automatic resets:');
        $this->line('   * * * * * cd '.base_path().' && php artisan schedule:run >> /dev/null 2>&1');
        $this->newLine();

        return self::SUCCESS;
    }
}
