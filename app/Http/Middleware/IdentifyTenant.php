<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Services\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is not authenticated, skip tenant identification
        if (! $request->user()) {
            return $next($request);
        }

        // If user is super admin, skip tenant identification (they don't belong to any tenant)
        if ($request->user()->isSuperAdmin()) {
            return $next($request);
        }

        // For regular users, set tenant from their tenant_id
        $tenantId = $request->user()->tenant_id;

        // If user has no tenant_id, they're not properly configured
        if (! $tenantId) {
            abort(403, 'Your account is not associated with any clinic. Please contact support.');
        }

        // Load the tenant
        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            abort(404, 'Clinic not found. Please contact support.');
        }

        // Check if tenant is active
        if ($tenant->status !== 'active') {
            abort(403, 'Your clinic is currently '.$tenant->status.'. Please contact support.');
        }

        // Set tenant in context
        $this->tenantContext->setTenant($tenant);

        return $next($request);
    }
}
