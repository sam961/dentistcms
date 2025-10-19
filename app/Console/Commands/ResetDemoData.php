<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Tenant;
use App\Models\Treatment;
use App\Models\User;
use Database\Seeders\DemoAdminSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetDemoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:reset {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset demo account data to initial state (runs automatically every hour)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('Are you sure you want to reset the demo data?')) {
            $this->info('Reset cancelled.');

            return self::SUCCESS;
        }

        $this->info('🔄 Resetting demo account data...');

        // Find demo tenant
        $demoTenant = Tenant::where('subdomain', 'demo')->first();

        if (! $demoTenant) {
            $this->error('Demo tenant not found. Run DemoAdminSeeder first.');

            return self::FAILURE;
        }

        DB::transaction(function () use ($demoTenant) {
            // Delete all demo tenant data
            $this->info('🗑️  Clearing existing demo data...');

            Appointment::where('tenant_id', $demoTenant->id)->delete();
            Patient::where('tenant_id', $demoTenant->id)->delete();
            Dentist::where('tenant_id', $demoTenant->id)->delete();
            Treatment::where('tenant_id', $demoTenant->id)->delete();

            // Keep only the demo admin user, delete other tenant users
            User::where('tenant_id', $demoTenant->id)
                ->where('email', '!=', DemoAdminSeeder::DEMO_EMAIL)
                ->delete();

            $this->info('🌱 Reseeding demo data...');

            // Reseed with fresh data
            $this->call('db:seed', [
                '--class' => 'Database\\Seeders\\TenantDataSeeder',
                '--force' => true,
            ]);
        });

        $this->newLine();
        $this->info('✅ Demo data reset successfully!');
        $this->line('🕐 Next reset: '.now()->addHour()->format('Y-m-d H:i:s'));
        $this->newLine();

        return self::SUCCESS;
    }
}
