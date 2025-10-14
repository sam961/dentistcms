<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class ProcessSubscriptionGracePeriods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:process-grace-periods';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start grace periods for expired subscriptions and process expired grace periods';

    /**
     * Execute the console command.
     */
    public function handle(SubscriptionService $subscriptionService)
    {
        $this->info('Starting grace periods for expired subscriptions...');
        $started = $subscriptionService->startGracePeriods();
        $this->info("Started {$started} grace period(s).");

        $this->info('Processing expired grace periods...');
        $expired = $subscriptionService->processExpiredGracePeriods();
        $this->info("Processed {$expired} expired grace period(s).");

        $this->info('Done!');

        return Command::SUCCESS;
    }
}
