<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantSubscriptionHistory;

class RevenueAnalyticsController extends Controller
{
    public function index()
    {
        // Revenue Analytics
        $revenueStats = $this->calculateRevenueStats();

        // Subscription Analytics
        $subscriptionStats = $this->calculateSubscriptionStats();

        // Revenue Trend (Last 6 months)
        $revenueTrend = $this->calculateRevenueTrend();

        // Revenue by Month (Last 12 months for detailed view)
        $revenueByMonth = $this->calculateRevenueByMonth();

        return view('admin.revenue-analytics', compact('revenueStats', 'subscriptionStats', 'revenueTrend', 'revenueByMonth'));
    }

    private function calculateRevenueStats(): array
    {
        // Total Revenue (all time)
        $totalRevenue = TenantSubscriptionHistory::sum('amount');

        // Monthly Recurring Revenue (active subscriptions)
        $activeSubscriptions = Tenant::where('subscription_status', 'active')
            ->where('subscription_ends_at', '>', now())
            ->get();

        $mrr = 0;
        foreach ($activeSubscriptions as $tenant) {
            $latestSubscription = $tenant->subscriptionHistory()
                ->where('starts_at', $tenant->subscription_starts_at)
                ->first();

            if ($latestSubscription) {
                $months = $latestSubscription->starts_at->diffInMonths($latestSubscription->ends_at);
                $mrr += $months > 0 ? $latestSubscription->amount / $months : 0;
            }
        }

        // Average Revenue Per Client
        $totalClients = Tenant::count();
        $arpc = $totalClients > 0 ? $totalRevenue / $totalClients : 0;

        // Revenue This Month
        $revenueThisMonth = TenantSubscriptionHistory::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Revenue Last Month
        $revenueLastMonth = TenantSubscriptionHistory::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');

        // Month-over-Month Growth
        $momGrowth = $revenueLastMonth > 0 ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 : 0;

        return [
            'total_revenue' => $totalRevenue,
            'mrr' => $mrr,
            'arpc' => $arpc,
            'revenue_this_month' => $revenueThisMonth,
            'revenue_last_month' => $revenueLastMonth,
            'mom_growth' => $momGrowth,
        ];
    }

    private function calculateSubscriptionStats(): array
    {
        // Active Subscriptions
        $activeSubscriptions = Tenant::where('subscription_status', 'active')
            ->where('subscription_ends_at', '>', now())
            ->count();

        // Expiring Soon (next 7 days)
        $expiringSoon = Tenant::where('subscription_status', 'active')
            ->whereBetween('subscription_ends_at', [now(), now()->addDays(7)])
            ->count();

        // Expiring This Month
        $expiringThisMonth = Tenant::where('subscription_status', 'active')
            ->whereBetween('subscription_ends_at', [now(), now()->addDays(30)])
            ->count();

        // Overdue (expired but still active status)
        $overdue = Tenant::where('subscription_status', 'active')
            ->where('subscription_ends_at', '<', now())
            ->count();

        // Trial/Free Accounts (no active subscription)
        $trialAccounts = Tenant::where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('subscription_status')
                    ->orWhere('subscription_status', '!=', 'active')
                    ->orWhereNull('subscription_ends_at');
            })
            ->count();

        // Upcoming Renewals (next 30 days) with details
        $upcomingRenewals = Tenant::where('subscription_status', 'active')
            ->where('subscription_ends_at', '>', now())
            ->where('subscription_ends_at', '<=', now()->addDays(30))
            ->with('subscriptionHistory')
            ->orderBy('subscription_ends_at', 'asc')
            ->get();

        return [
            'active_subscriptions' => $activeSubscriptions,
            'expiring_soon' => $expiringSoon,
            'expiring_this_month' => $expiringThisMonth,
            'overdue' => $overdue,
            'trial_accounts' => $trialAccounts,
            'upcoming_renewals' => $upcomingRenewals,
        ];
    }

    private function calculateRevenueTrend(): array
    {
        $months = [];
        $revenues = [];

        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');

            $revenue = TenantSubscriptionHistory::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');

            $months[] = $monthName;
            $revenues[] = $revenue;
        }

        return [
            'months' => $months,
            'revenues' => $revenues,
        ];
    }

    private function calculateRevenueByMonth(): array
    {
        $months = [];

        // Get last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);

            $revenue = TenantSubscriptionHistory::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');

            $subscriptionCount = TenantSubscriptionHistory::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            $months[] = [
                'month' => $date->format('F Y'),
                'month_short' => $date->format('M Y'),
                'revenue' => $revenue,
                'subscription_count' => $subscriptionCount,
            ];
        }

        return $months;
    }
}
