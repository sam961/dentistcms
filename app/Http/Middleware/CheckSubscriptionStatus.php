<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for super admins
        if (auth()->check() && auth()->user()->is_super_admin) {
            return $next($request);
        }

        // Get current tenant and refresh from database
        $tenant = app(\App\Services\TenantContext::class)->getTenant();

        // Refresh tenant data from database to get latest subscription status
        if ($tenant) {
            $tenant = $tenant->fresh();
        }

        // If no tenant or subscription is not active or expired
        if (! $tenant ||
            $tenant->subscription_status !== 'active' ||
            ! $tenant->subscription_ends_at ||
            $tenant->subscription_ends_at->isPast()) {

            // Allow access to subscription-expired page and logout
            if ($request->routeIs('subscription.expired') || $request->routeIs('logout')) {
                return $next($request);
            }

            // Redirect to subscription expired page
            return redirect()->route('subscription.expired');
        }

        return $next($request);
    }
}
