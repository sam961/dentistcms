<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\TenantSubscriptionHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    /**
     * Get tier configuration
     */
    public function getTierConfig(string $tier): ?array
    {
        return config("subscription.tiers.{$tier}");
    }

    /**
     * Get tier price
     */
    public function getTierPrice(string $tier): float
    {
        return (float) config("subscription.tiers.{$tier}.price", 0);
    }

    /**
     * Calculate subscription end date based on tier
     */
    public function calculateEndDate(string $tier, Carbon $startDate = null): ?Carbon
    {
        $startDate = $startDate ?? now();

        return match ($tier) {
            'monthly' => $startDate->copy()->addMonth(),
            'yearly' => $startDate->copy()->addYear(),
            'free' => null, // Free tier never expires
            default => null,
        };
    }

    /**
     * Change tenant subscription
     * Handles immediate upgrades and scheduled downgrades
     */
    public function changeTierSubscription(Tenant $tenant, string $newTier, ?string $reason = null): array
    {
        $currentTier = $tenant->subscription_tier;
        $currentEndsAt = $tenant->subscription_ends_at;

        // If current tier is free or expired, apply immediately
        if ($currentTier === 'free' || !$currentEndsAt || $currentEndsAt->isPast()) {
            return $this->applyImmediateChange($tenant, $newTier, $reason);
        }

        // Determine if this is an upgrade or downgrade
        $isUpgrade = $this->isUpgrade($currentTier, $newTier);

        if ($isUpgrade) {
            // Upgrades apply immediately
            return $this->applyImmediateChange($tenant, $newTier, $reason);
        } else {
            // Downgrades scheduled for end of current period
            return $this->scheduleChange($tenant, $newTier, $currentEndsAt, $reason);
        }
    }

    /**
     * Apply immediate tier change
     */
    protected function applyImmediateChange(Tenant $tenant, string $newTier, ?string $reason = null): array
    {
        $oldTier = $tenant->subscription_tier;
        $previousScheduledTier = $tenant->scheduled_tier;
        $startsAt = now();
        $endsAt = $this->calculateEndDate($newTier, $startsAt);
        $amount = $this->getTierPrice($newTier);

        // Determine action type
        $action = 'created';
        if ($oldTier && $oldTier !== 'free') {
            $action = $this->isUpgrade($oldTier, $newTier) ? 'upgraded' : 'downgraded';
        }

        // Build notes
        $notes = $reason;
        if ($previousScheduledTier && !$notes) {
            $notes = "Cancelled scheduled change to {$previousScheduledTier}";
        } elseif ($previousScheduledTier && $notes) {
            $notes .= " (cancelled scheduled change to {$previousScheduledTier})";
        }

        // Update tenant
        $tenant->update([
            'subscription_tier' => $newTier,
            'subscription_status' => 'active',
            'subscription_starts_at' => $startsAt,
            'subscription_ends_at' => $endsAt,
            'next_billing_date' => $endsAt,
            'auto_renew' => $newTier !== 'free' ? config('subscription.auto_renew_default', true) : false,
            'payment_status' => $newTier === 'free' ? 'none' : 'paid',
            'last_payment_date' => $newTier !== 'free' ? $startsAt : null,
            'scheduled_tier' => null,
            'scheduled_change_date' => null,
            'scheduled_change_reason' => null,
            'grace_period_ends_at' => null,
        ]);

        // Log history
        TenantSubscriptionHistory::create([
            'tenant_id' => $tenant->id,
            'action' => $action,
            'subscription_tier' => $newTier,
            'amount' => $amount,
            'billing_cycle' => $newTier === 'free' ? null : ($newTier === 'monthly' ? 'monthly' : 'yearly'),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
            'notes' => $notes,
            'performed_by' => Auth::id(),
        ]);

        Log::info("Subscription changed immediately", [
            'tenant_id' => $tenant->id,
            'old_tier' => $oldTier,
            'new_tier' => $newTier,
            'action' => $action,
        ]);

        return [
            'success' => true,
            'immediate' => true,
            'message' => "Subscription {$action} to {$newTier} successfully!",
            'action' => $action,
        ];
    }

    /**
     * Schedule a tier change for end of current period
     */
    protected function scheduleChange(Tenant $tenant, string $newTier, Carbon $changeDate, ?string $reason = null): array
    {
        $previousScheduledTier = $tenant->scheduled_tier;

        // Check if trying to schedule the same tier that's already scheduled
        if ($previousScheduledTier === $newTier) {
            return [
                'success' => true,
                'immediate' => false,
                'message' => "Change to {$newTier} is already scheduled for " . $changeDate->format('M d, Y'),
                'action' => 'scheduled',
                'change_date' => $changeDate,
            ];
        }

        // Update tenant with new scheduled change
        $tenant->update([
            'scheduled_tier' => $newTier,
            'scheduled_change_date' => $changeDate,
            'scheduled_change_reason' => $reason ?? 'User requested downgrade',
        ]);

        // Log scheduled change
        $notes = $reason ?? "Scheduled to change from {$tenant->subscription_tier} to {$newTier} on " . $changeDate->format('Y-m-d');
        if ($previousScheduledTier) {
            $notes .= " (replaced previous scheduled change to {$previousScheduledTier})";
        }

        TenantSubscriptionHistory::create([
            'tenant_id' => $tenant->id,
            'action' => 'scheduled',
            'subscription_tier' => $newTier,
            'amount' => $this->getTierPrice($newTier),
            'billing_cycle' => $newTier === 'free' ? null : ($newTier === 'monthly' ? 'monthly' : 'yearly'),
            'starts_at' => $changeDate,
            'ends_at' => $this->calculateEndDate($newTier, $changeDate),
            'status' => 'scheduled',
            'notes' => $notes,
            'performed_by' => Auth::id(),
        ]);

        Log::info("Subscription change scheduled", [
            'tenant_id' => $tenant->id,
            'current_tier' => $tenant->subscription_tier,
            'scheduled_tier' => $newTier,
            'previous_scheduled_tier' => $previousScheduledTier,
            'change_date' => $changeDate->toDateString(),
        ]);

        $message = "Subscription will change to {$newTier} on " . $changeDate->format('M d, Y');
        if ($previousScheduledTier) {
            $message .= " (replaced scheduled change to {$previousScheduledTier})";
        }

        return [
            'success' => true,
            'immediate' => false,
            'message' => $message,
            'action' => 'scheduled',
            'change_date' => $changeDate,
        ];
    }

    /**
     * Cancel scheduled tier change
     */
    public function cancelScheduledChange(Tenant $tenant): bool
    {
        if (!$tenant->scheduled_tier) {
            return false;
        }

        $tenant->update([
            'scheduled_tier' => null,
            'scheduled_change_date' => null,
            'scheduled_change_reason' => null,
        ]);

        Log::info("Scheduled subscription change cancelled", [
            'tenant_id' => $tenant->id,
        ]);

        return true;
    }

    /**
     * Process scheduled subscription changes (run via cron)
     */
    public function processScheduledChanges(): int
    {
        $tenantsToChange = Tenant::whereNotNull('scheduled_tier')
            ->whereNotNull('scheduled_change_date')
            ->where('scheduled_change_date', '<=', now())
            ->get();

        $processed = 0;

        foreach ($tenantsToChange as $tenant) {
            $this->applyImmediateChange(
                $tenant,
                $tenant->scheduled_tier,
                $tenant->scheduled_change_reason ?? 'Scheduled change applied'
            );
            $processed++;
        }

        Log::info("Processed scheduled subscription changes", [
            'count' => $processed,
        ]);

        return $processed;
    }

    /**
     * Renew subscription (for manual activation or auto-renewal)
     */
    public function renewSubscription(Tenant $tenant, bool $manual = true): array
    {
        if ($tenant->subscription_tier === 'free') {
            return [
                'success' => false,
                'message' => 'Cannot renew free tier subscription',
            ];
        }

        // For manual renewal, always proceed. For auto-renewal, check if enabled
        if (!$manual && !$tenant->auto_renew) {
            return [
                'success' => false,
                'message' => 'Auto-renewal is not enabled',
            ];
        }

        $currentTier = $tenant->subscription_tier;
        $startsAt = $tenant->subscription_ends_at ?? now();
        $endsAt = $this->calculateEndDate($currentTier, $startsAt);
        $amount = $this->getTierPrice($currentTier);

        // Update tenant
        $tenant->update([
            'subscription_starts_at' => $startsAt,
            'subscription_ends_at' => $endsAt,
            'next_billing_date' => $endsAt,
            'subscription_status' => 'active',
            'payment_status' => 'paid',
            'last_payment_date' => now(),
            'grace_period_ends_at' => null,
        ]);

        // Log renewal
        TenantSubscriptionHistory::create([
            'tenant_id' => $tenant->id,
            'action' => 'renewed',
            'subscription_tier' => $currentTier,
            'amount' => $amount,
            'billing_cycle' => $currentTier === 'monthly' ? 'monthly' : 'yearly',
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
            'notes' => 'Auto-renewal processed',
            'performed_by' => null, // System action
        ]);

        Log::info("Subscription renewed", [
            'tenant_id' => $tenant->id,
            'tier' => $currentTier,
            'amount' => $amount,
        ]);

        return [
            'success' => true,
            'message' => 'Subscription renewed successfully',
            'ends_at' => $endsAt,
        ];
    }

    /**
     * Process auto-renewals (run via cron) - Only for tenants with auto-renew enabled
     */
    public function processAutoRenewals(): int
    {
        $tenantsToRenew = Tenant::where('auto_renew', true)
            ->whereIn('subscription_tier', ['monthly', 'yearly'])
            ->where('subscription_status', 'active')
            ->whereDate('subscription_ends_at', '<=', now())
            ->get();

        $processed = 0;

        foreach ($tenantsToRenew as $tenant) {
            $result = $this->renewSubscription($tenant, false); // false = auto-renewal
            if ($result['success']) {
                $processed++;
            }
        }

        Log::info("Processed auto-renewals", [
            'count' => $processed,
        ]);

        return $processed;
    }

    /**
     * Start grace periods for expired subscriptions (run via cron)
     */
    public function startGracePeriods(): int
    {
        $gracePeriodDays = config('subscription.grace_period_days', 7);

        $expiredTenants = Tenant::whereIn('subscription_tier', ['monthly', 'yearly'])
            ->where('subscription_status', 'active')
            ->whereDate('subscription_ends_at', '<', now())
            ->whereNull('grace_period_ends_at')
            ->get();

        $processed = 0;

        foreach ($expiredTenants as $tenant) {
            $gracePeriodEnds = now()->addDays($gracePeriodDays);

            $tenant->update([
                'subscription_status' => 'past_due',
                'grace_period_ends_at' => $gracePeriodEnds,
            ]);

            TenantSubscriptionHistory::create([
                'tenant_id' => $tenant->id,
                'action' => 'expired',
                'subscription_tier' => $tenant->subscription_tier,
                'amount' => $this->getTierPrice($tenant->subscription_tier),
                'billing_cycle' => $tenant->subscription_tier === 'monthly' ? 'monthly' : 'yearly',
                'starts_at' => $tenant->subscription_starts_at,
                'ends_at' => $tenant->subscription_ends_at,
                'status' => 'past_due',
                'notes' => "Subscription expired. Grace period until {$gracePeriodEnds->format('Y-m-d')}",
                'performed_by' => null,
            ]);

            $processed++;
        }

        Log::info("Started grace periods", [
            'count' => $processed,
        ]);

        return $processed;
    }

    /**
     * Process expired grace periods (run via cron)
     */
    public function processExpiredGracePeriods(): int
    {
        $tenantsToExpire = Tenant::whereIn('subscription_tier', ['monthly', 'yearly'])
            ->where('subscription_status', 'past_due')
            ->whereNotNull('grace_period_ends_at')
            ->whereDate('grace_period_ends_at', '<', now())
            ->get();

        $processed = 0;

        foreach ($tenantsToExpire as $tenant) {
            $tenant->update([
                'subscription_status' => 'expired',
            ]);

            TenantSubscriptionHistory::create([
                'tenant_id' => $tenant->id,
                'action' => 'expired',
                'subscription_tier' => $tenant->subscription_tier,
                'amount' => $this->getTierPrice($tenant->subscription_tier),
                'billing_cycle' => $tenant->subscription_tier === 'monthly' ? 'monthly' : 'yearly',
                'starts_at' => $tenant->subscription_starts_at,
                'ends_at' => $tenant->subscription_ends_at,
                'status' => 'expired',
                'notes' => 'Grace period ended. Subscription fully expired.',
                'performed_by' => null,
            ]);

            $processed++;
        }

        Log::info("Processed expired grace periods", [
            'count' => $processed,
        ]);

        return $processed;
    }

    /**
     * Cancel subscription (applies at end of period)
     */
    public function cancelSubscription(Tenant $tenant, ?string $reason = null): array
    {
        if ($tenant->subscription_tier === 'free') {
            return [
                'success' => false,
                'message' => 'Free tier cannot be cancelled',
            ];
        }

        $endsAt = $tenant->subscription_ends_at ?? now();

        // Schedule downgrade to free tier
        $result = $this->scheduleChange($tenant, 'free', $endsAt, $reason ?? 'User cancelled subscription');

        // Mark as cancelled but keep active until end date
        $tenant->update([
            'subscription_status' => 'cancelled',
            'auto_renew' => false,
        ]);

        return $result;
    }

    /**
     * Determine if tier change is an upgrade
     */
    protected function isUpgrade(string $fromTier, string $toTier): bool
    {
        $tierHierarchy = ['free' => 0, 'monthly' => 1, 'yearly' => 2];

        return ($tierHierarchy[$toTier] ?? 0) > ($tierHierarchy[$fromTier] ?? 0);
    }

    /**
     * Check if tenant has exceeded usage limits
     */
    public function checkUsageLimits(Tenant $tenant): array
    {
        $config = $this->getTierConfig($tenant->subscription_tier);
        $limits = $config['limits'] ?? [];
        $exceeded = [];

        if (isset($limits['max_users']) && $limits['max_users'] !== null) {
            $userCount = $tenant->users()->count();
            if ($userCount >= $limits['max_users']) {
                $exceeded['users'] = [
                    'current' => $userCount,
                    'limit' => $limits['max_users'],
                ];
            }
        }

        if (isset($limits['max_patients']) && $limits['max_patients'] !== null) {
            $patientCount = $tenant->patients()->count();
            if ($patientCount >= $limits['max_patients']) {
                $exceeded['patients'] = [
                    'current' => $patientCount,
                    'limit' => $limits['max_patients'],
                ];
            }
        }

        if (isset($limits['max_dentists']) && $limits['max_dentists'] !== null) {
            $dentistCount = $tenant->dentists()->count();
            if ($dentistCount >= $limits['max_dentists']) {
                $exceeded['dentists'] = [
                    'current' => $dentistCount,
                    'limit' => $limits['max_dentists'],
                ];
            }
        }

        return $exceeded;
    }
}
