<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class ProcessScheduledSubscriptionChanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:process-scheduled-changes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled subscription tier changes for tenants';

    /**
     * Execute the console command.
     */
    public function handle(SubscriptionService $subscriptionService)
    {
        $this->info('Processing scheduled subscription changes...');

        $processed = $subscriptionService->processScheduledChanges();

        $this->info("Processed {$processed} scheduled change(s).");

        return Command::SUCCESS;
    }
}
