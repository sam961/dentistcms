<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Services\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $host = $request->getHost();
        $subdomain = $this->extractSubdomain($host);

        // If no subdomain, this is the base domain - allow it
        if (! $subdomain) {
            return $next($request);
        }

        // Check if subdomain is reserved (admin, www, api, etc.)
        if ($this->isReservedSubdomain($subdomain)) {
            // Reserved subdomains bypass tenant identification
            return $next($request);
        }

        // At this point, we have a non-reserved subdomain
        // It MUST exist in the domains table as a valid tenant

        // Look up tenant by subdomain in domains table
        $appDomain = config('app.domain', 'dentistcms.test');
        $domain = DB::table('domains')
            ->where('domain', $subdomain.'.'.$appDomain)
            ->orWhere('domain', $host)
            ->first();

        if (! $domain) {
            // Subdomain not found - this is an invalid/non-existent clinic
            abort(404, 'Clinic not found. The subdomain "'.$subdomain.'" is not registered. Please check the URL or contact support.');
        }

        // Load the tenant
        $tenant = Tenant::find($domain->tenant_id);

        if (! $tenant) {
            abort(404, 'Clinic not found.');
        }

        // Check if tenant is active
        if ($tenant->status !== 'active') {
            abort(403, 'This clinic is currently '.$tenant->status.'. Please contact support.');
        }

        // Set tenant in context
        $this->tenantContext->setTenant($tenant);

        return $next($request);
    }

    /**
     * Check if subdomain is reserved and cannot be used by tenants
     */
    protected function isReservedSubdomain(string $subdomain): bool
    {
        $reserved = config('tenancy.reserved_subdomains', [
            'www', 'admin', 'api', 'mail', 'ftp', 'localhost',
            'webmail', 'smtp', 'pop', 'ns1', 'ns2', 'cpanel',
            'whm', 'webdisk', 'blog', 'shop',
        ]);

        return in_array(strtolower($subdomain), $reserved);
    }

    /**
     * Extract subdomain from host
     */
    protected function extractSubdomain(string $host): ?string
    {
        // Remove port if present
        $host = explode(':', $host)[0];

        // Get the configured app domain (e.g., dentistcms.test or dentistcms.com)
        $appDomain = config('app.domain', 'dentistcms.test');

        // Check if host matches the app domain pattern: subdomain.{appDomain}
        if (str_ends_with($host, ".{$appDomain}")) {
            $parts = explode('.', $host);
            // For pattern: subdomain.domain.tld (e.g., beauty-smile.dentistcms.test)
            $domainParts = explode('.', $appDomain);
            if (count($parts) > count($domainParts)) {
                return $parts[0];
            }
        }

        // For localhost testing: beauty-smile.localhost
        if (str_ends_with($host, '.localhost')) {
            return explode('.', $host)[0];
        }

        return null;
    }
}
