<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CleanOrphanedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:clean-orphaned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove users whose tenant has been deleted';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Searching for orphaned users...');

        // Find users with tenant_id that points to non-existent tenant
        // Exclude super admins who don't need a tenant
        $orphanedUsers = User::whereNotNull('tenant_id')
            ->whereDoesntHave('tenant')
            ->where('is_super_admin', false)
            ->get();

        if ($orphanedUsers->isEmpty()) {
            $this->info('No orphaned users found.');

            return self::SUCCESS;
        }

        $this->warn("Found {$orphanedUsers->count()} orphaned user(s):");
        $this->newLine();

        $this->table(
            ['ID', 'Name', 'Email', 'Role', 'Tenant ID'],
            $orphanedUsers->map(fn ($user) => [
                $user->id,
                $user->name,
                $user->email,
                $user->role,
                $user->tenant_id,
            ])
        );

        $this->newLine();

        if ($this->confirm('Do you want to delete these users?', false)) {
            $count = $orphanedUsers->count();

            foreach ($orphanedUsers as $user) {
                $user->delete();
            }

            $this->info("✓ Successfully deleted {$count} orphaned user(s).");

            return self::SUCCESS;
        }

        $this->info('Operation cancelled.');

        return self::SUCCESS;
    }
}
