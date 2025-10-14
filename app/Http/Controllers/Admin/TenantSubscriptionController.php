<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantSubscriptionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TenantSubscriptionController extends Controller
{
    /**
     * Show subscription management page for a tenant
     */
    public function index(Tenant $tenant)
    {
        $tenant->load('subscriptionHistory.performedBy');
        $tiers = config('subscription.tiers');

        return view('admin.tenants.subscription', compact('tenant', 'tiers'));
    }

    /**
     * Create a new subscription (client paid cash)
     */
    public function store(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'custom_amount' => 'required|numeric|min:0',
            'custom_duration_months' => 'required|integer|min:1|max:60',
            'notes' => 'nullable|string',
        ]);

        $tier = 'paid';
        $tierConfig = config("subscription.tiers.{$tier}");

        // Cast to proper types
        $amount = (float) $validated['custom_amount'];
        $durationMonths = (int) $validated['custom_duration_months'];

        // Calculate dates - check if there's an active subscription
        if ($tenant->subscription_status === 'active' && $tenant->subscription_ends_at && $tenant->subscription_ends_at->isFuture()) {
            // If there's an active subscription, the new one starts when current ends (continuous)
            $startsAt = $tenant->subscription_ends_at->copy();
            $endsAt = $startsAt->copy()->addMonths($durationMonths);
        } else {
            // No active subscription, start immediately
            $startsAt = now();
            $endsAt = $startsAt->copy()->addMonths($durationMonths);
        }

        // Update tenant subscription
        $tenant->update([
            'subscription_tier' => $tier,
            'subscription_status' => 'active',
            'subscription_starts_at' => $startsAt,
            'subscription_ends_at' => $endsAt,
            'next_billing_date' => $endsAt,
            'payment_status' => 'paid',
            'last_payment_date' => now(),
            'auto_renew' => false,
            'scheduled_tier' => null,
            'scheduled_change_date' => null,
            'grace_period_ends_at' => null,
        ]);

        // Log in history
        TenantSubscriptionHistory::create([
            'tenant_id' => $tenant->id,
            'action' => 'created',
            'subscription_tier' => $tier,
            'amount' => $amount,
            'billing_cycle' => 'custom',
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
            'notes' => $validated['notes'] ?? "Subscription created - Client paid ${amount} for {$durationMonths} months",
            'performed_by' => Auth::id(),
        ]);

        $message = $startsAt->isToday()
            ? "Subscription created successfully! Expires on " . $endsAt->format('M d, Y')
            : "Subscription created successfully! Starts on " . $startsAt->format('M d, Y') . " and expires on " . $endsAt->format('M d, Y') . " (continuous from current subscription)";

        return redirect()
            ->route('admin.tenants.subscription', $tenant)
            ->with('success', $message);
    }

    /**
     * Update existing subscription (edit tier, amount, duration)
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'custom_amount' => 'required|numeric|min:0',
            'custom_duration_months' => 'required|integer|min:1|max:60',
            'notes' => 'nullable|string',
        ]);

        $tier = 'paid';
        $tierConfig = config("subscription.tiers.{$tier}");

        // Cast to proper types
        $amount = (float) $validated['custom_amount'];
        $durationMonths = (int) $validated['custom_duration_months'];

        // Keep the original start date
        $startsAt = $tenant->subscription_starts_at ?? now();

        // Calculate new end date
        $endsAt = $startsAt->copy()->addMonths($durationMonths);

        // Update tenant subscription
        $tenant->update([
            'subscription_tier' => $tier,
            'subscription_status' => 'active',
            'subscription_ends_at' => $endsAt,
            'next_billing_date' => $endsAt,
            'payment_status' => 'paid',
            'last_payment_date' => now(),
        ]);

        // Update the existing history record instead of creating a new one
        $currentHistory = TenantSubscriptionHistory::where('tenant_id', $tenant->id)
            ->where('starts_at', $startsAt)
            ->whereIn('action', ['created', 'updated'])
            ->latest()
            ->first();

        if ($currentHistory) {
            $currentHistory->update([
                'amount' => $amount,
                'ends_at' => $endsAt,
                'action' => 'updated',
                'notes' => $validated['notes'] ?? $currentHistory->notes,
            ]);
        }

        $message = "Subscription updated successfully! Expires on " . $endsAt->format('M d, Y');

        return redirect()
            ->route('admin.tenants.subscription', $tenant)
            ->with('success', $message);
    }

    /**
     * Set subscription to expired (manually expire before end date)
     */
    public function expire(Tenant $tenant)
    {
        if ($tenant->subscription_status === 'expired') {
            return redirect()
                ->route('admin.tenants.subscription', $tenant)
                ->with('error', 'Subscription is already expired.');
        }

        // Mark as expired immediately
        $tenant->update([
            'subscription_status' => 'expired',
            'subscription_ends_at' => now(),
            'payment_status' => 'expired',
        ]);

        // Log in history
        TenantSubscriptionHistory::create([
            'tenant_id' => $tenant->id,
            'action' => 'expired',
            'subscription_tier' => 'paid',
            'amount' => 0,
            'billing_cycle' => 'custom',
            'starts_at' => $tenant->subscription_starts_at,
            'ends_at' => now(),
            'status' => 'expired',
            'notes' => "Subscription manually expired by admin",
            'performed_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.tenants.subscription', $tenant)
            ->with('success', 'Subscription has been expired.');
    }

    /**
     * Remove a subscription by its history ID
     */
    public function destroy(Request $request, Tenant $tenant)
    {
        // Get the subscription start date from the request (passed as a parameter)
        $startDate = $request->input('start_date');

        if (!$startDate) {
            return redirect()
                ->route('admin.tenants.subscription', $tenant)
                ->with('error', 'Invalid subscription to delete.');
        }

        // Find and delete the specific subscription history record
        $subscription = TenantSubscriptionHistory::where('tenant_id', $tenant->id)
            ->where('starts_at', $startDate)
            ->first();

        if (!$subscription) {
            return redirect()
                ->route('admin.tenants.subscription', $tenant)
                ->with('error', 'Subscription not found.');
        }

        $isCurrentSubscription = $tenant->subscription_starts_at
            && $tenant->subscription_starts_at->equalTo($subscription->starts_at);

        // Delete the subscription history record
        $subscription->delete();

        // Only reset tenant fields if we're deleting the CURRENT active subscription
        if ($isCurrentSubscription) {
            // Check if there's a previous subscription to fall back to
            $previousSubscription = TenantSubscriptionHistory::where('tenant_id', $tenant->id)
                ->whereIn('action', ['created', 'updated'])
                ->latest('starts_at')
                ->first();

            if ($previousSubscription && $previousSubscription->ends_at && $previousSubscription->ends_at->isFuture()) {
                // Restore the previous subscription as active
                $tenant->update([
                    'subscription_tier' => 'paid',
                    'subscription_status' => 'active',
                    'subscription_starts_at' => $previousSubscription->starts_at,
                    'subscription_ends_at' => $previousSubscription->ends_at,
                    'next_billing_date' => $previousSubscription->ends_at,
                    'payment_status' => 'paid',
                ]);
                $message = 'Subscription removed. Previous subscription is now active.';
            } else {
                // No previous subscription, reset to expired
                $tenant->update([
                    'subscription_tier' => 'paid',
                    'subscription_status' => 'expired',
                    'subscription_starts_at' => null,
                    'subscription_ends_at' => null,
                    'next_billing_date' => null,
                    'payment_status' => 'none',
                    'last_payment_date' => null,
                    'auto_renew' => false,
                    'scheduled_tier' => null,
                    'scheduled_change_date' => null,
                    'grace_period_ends_at' => null,
                ]);
                $message = 'Subscription removed successfully.';
            }
        } else {
            // Deleting a future subscription, keep current one active
            $message = 'Future subscription removed. Current subscription is still active.';
        }

        return redirect()
            ->route('admin.tenants.subscription', $tenant)
            ->with('success', $message);
    }
}
