<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantSubscriptionHistory;

class SubscriptionAnalyticsController extends Controller
{
    public function index()
    {
        // Total Revenue (All Time)
        $totalRevenue = TenantSubscriptionHistory::whereIn('action', ['created', 'updated'])
            ->sum('amount');

        // Monthly Recurring Revenue (Active subscriptions that will renew this month)
        $mrr = Tenant::where('subscription_status', 'active')
            ->whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '>=', now())
            ->get()
            ->sum(function ($tenant) {
                $history = $tenant->subscriptionHistory()
                    ->whereIn('action', ['created', 'updated'])
                    ->latest()
                    ->first();

                if (! $history || ! $history->starts_at || ! $history->ends_at) {
                    return 0;
                }

                $months = $history->starts_at->diffInMonths($history->ends_at) ?: 1;

                return $history->amount / $months;
            });

        // Revenue This Month
        $revenueThisMonth = TenantSubscriptionHistory::whereIn('action', ['created', 'updated'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Revenue Last Month
        $revenueLastMonth = TenantSubscriptionHistory::whereIn('action', ['created', 'updated'])
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');

        // Active Subscriptions
        $activeSubscriptions = Tenant::where('subscription_status', 'active')
            ->whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '>=', now())
            ->count();

        // Expired Subscriptions
        $expiredSubscriptions = Tenant::where('subscription_status', 'expired')
            ->orWhere(function ($query) {
                $query->where('subscription_status', 'active')
                    ->whereNotNull('subscription_ends_at')
                    ->where('subscription_ends_at', '<', now());
            })
            ->count();

        // Expiring Soon (Next 30 days)
        $expiringSoon = Tenant::where('subscription_status', 'active')
            ->whereNotNull('subscription_ends_at')
            ->whereBetween('subscription_ends_at', [now(), now()->addDays(30)])
            ->count();

        // Total Tenants
        $totalTenants = Tenant::count();

        // Average Revenue Per Customer
        $arpc = $activeSubscriptions > 0 ? $totalRevenue / $activeSubscriptions : 0;

        // Revenue Growth Rate
        $growthRate = 0;
        if ($revenueLastMonth > 0) {
            $growthRate = (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100;
        }

        // Recent Subscriptions (Last 10)
        $recentSubscriptions = TenantSubscriptionHistory::with('tenant')
            ->whereIn('action', ['created', 'updated'])
            ->latest()
            ->limit(10)
            ->get();

        // Revenue by Month (Last 6 months)
        $revenueByMonth = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = TenantSubscriptionHistory::whereIn('action', ['created', 'updated'])
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('amount');

            $revenueByMonth->push([
                'month' => $month->format('M Y'),
                'revenue' => $revenue,
            ]);
        }

        // Top Paying Clients
        $topClients = Tenant::withSum(['subscriptionHistory as total_paid' => function ($query) {
            $query->whereIn('action', ['created', 'updated']);
        }], 'amount')
            ->orderByDesc('total_paid')
            ->limit(10)
            ->get();

        // Subscription Status Breakdown
        $statusBreakdown = [
            'active' => Tenant::where('subscription_status', 'active')
                ->whereNotNull('subscription_ends_at')
                ->where('subscription_ends_at', '>=', now())
                ->count(),
            'expired' => $expiredSubscriptions,
            'trial' => Tenant::where('subscription_status', 'trial')->count(),
            'cancelled' => Tenant::where('subscription_status', 'cancelled')->count(),
        ];

        return view('admin.subscriptions.analytics', compact(
            'totalRevenue',
            'mrr',
            'revenueThisMonth',
            'revenueLastMonth',
            'activeSubscriptions',
            'expiredSubscriptions',
            'expiringSoon',
            'totalTenants',
            'arpc',
            'growthRate',
            'recentSubscriptions',
            'revenueByMonth',
            'topClients',
            'statusBreakdown'
        ));
    }
}
