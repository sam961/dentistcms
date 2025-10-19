<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class GenerateTenantSubdomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:generate-subdomains {--force : Overwrite existing subdomains}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-generate subdomains for tenants that don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Generating missing tenant subdomains...');
        $this->newLine();

        $query = $this->option('force')
            ? Tenant::query()
            : Tenant::whereNull('subdomain')->orWhere('subdomain', '');

        $tenants = $query->get();

        if ($tenants->isEmpty()) {
            $this->info('✓ All tenants already have subdomains!');

            return self::SUCCESS;
        }

        $this->info("Found {$tenants->count()} tenant(s) without subdomain.");
        $this->newLine();

        $bar = $this->output->createProgressBar($tenants->count());
        $bar->start();

        foreach ($tenants as $tenant) {
            // Auto-generate subdomain from name
            $subdomain = $this->generateSubdomain($tenant->name);

            // Check if subdomain exists, if so, add number
            $baseSubdomain = $subdomain;
            $counter = 1;
            while (Tenant::where('subdomain', $subdomain)->where('id', '!=', $tenant->id)->exists()) {
                $subdomain = $baseSubdomain.'-'.$counter;
                $counter++;
            }

            $tenant->subdomain = $subdomain;
            $tenant->save();

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Display results
        $this->table(
            ['Tenant Name', 'Subdomain', 'Full URL'],
            $tenants->map(fn ($tenant) => [
                $tenant->name,
                $tenant->subdomain,
                $tenant->subdomain.'.'.config('app.domain', 'yourdomain.com'),
            ])
        );

        $this->newLine();
        $this->info('✓ Successfully generated subdomains for all tenants!');

        return self::SUCCESS;
    }

    /**
     * Generate a subdomain from a name
     */
    private function generateSubdomain(string $name): string
    {
        $subdomain = strtolower($name);

        // Remove special characters except spaces and hyphens
        $subdomain = preg_replace('/[^a-z0-9\s-]/', '', $subdomain);

        // Replace spaces with hyphens
        $subdomain = preg_replace('/\s+/', '-', $subdomain);

        // Replace multiple hyphens with single
        $subdomain = preg_replace('/-+/', '-', $subdomain);

        // Remove leading/trailing hyphens
        $subdomain = trim($subdomain, '-');

        // If empty, generate random subdomain
        if (empty($subdomain)) {
            $subdomain = 'clinic-'.substr(md5(uniqid()), 0, 8);
        }

        return $subdomain;
    }
}
