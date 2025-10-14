<?php

namespace App\Http\Middleware;

use App\Services\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantUser
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
        // Check if user is authenticated
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // If user is super admin, redirect to admin dashboard
        if ($request->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Check if tenant context is set (from subdomain)
        if (! $this->tenantContext->hasTenant()) {
            abort(403, 'Access denied. No tenant context.');
        }

        // Check if user belongs to the current tenant
        if ($request->user()->tenant_id !== $this->tenantContext->getTenantId()) {
            abort(403, 'Access denied. You do not belong to this clinic.');
        }

        return $next($request);
    }
}
