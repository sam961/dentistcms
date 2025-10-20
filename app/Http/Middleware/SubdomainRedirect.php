<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubdomainRedirect
{
    /**
     * Handle an incoming request.
     *
     * This middleware redirects users from main domain to their appropriate subdomain:
     * - Regular users: Redirected to their tenant subdomain
     * - Demo access: Redirected to demo.general-station.com
     * - Super admin: Stays on main domain at /admin
     * - Landing page: Stays on main domain
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $appDomain = config('app.domain', 'general-station.com');
        $subdomain = $this->extractSubdomain($host, $appDomain);
        $path = $request->path();

        // Skip redirect for certain paths
        if ($this->shouldSkipRedirect($request)) {
            return $next($request);
        }

        // ================================================================
        // MAIN DOMAIN LOGIC (general-station.com)
        // ================================================================
        if (! $subdomain) {
            // Allow super admin routes on main domain
            if (str_starts_with($path, 'admin')) {
                return $next($request);
            }

            // Allow landing pages
            if ($path === '/' || $path === '' || str_starts_with($path, 'contact')) {
                return $next($request);
            }

            // If user tries to access /login or /dashboard on main domain
            if (str_starts_with($path, 'login') || str_starts_with($path, 'register')) {
                // For now, redirect to demo subdomain
                // Later: Show subdomain selection page or tenant-specific login
                $scheme = $request->isSecure() ? 'https' : 'http';
                $targetUrl = $scheme.'://demo.'.$appDomain.'/login';

                return redirect()->away($targetUrl);
            }

            // If authenticated regular user tries to access /dashboard on main domain
            if (str_starts_with($path, 'dashboard')) {
                if ($request->user() && ! $request->user()->isSuperAdmin()) {
                    $tenant = $request->user()->tenant;
                    if ($tenant && $tenant->subdomain) {
                        $scheme = $request->isSecure() ? 'https' : 'http';
                        $targetUrl = $scheme.'://'.$tenant->subdomain.'.'.$appDomain.'/dashboard';

                        return redirect()->away($targetUrl);
                    }
                }
            }

            // For any authenticated regular user on main domain, redirect to their subdomain
            if ($request->user() && ! $request->user()->isSuperAdmin()) {
                $tenant = $request->user()->tenant;
                if ($tenant && $tenant->subdomain) {
                    $scheme = $request->isSecure() ? 'https' : 'http';
                    $targetUrl = $scheme.'://'.$tenant->subdomain.'.'.$appDomain.'/'.$path;

                    return redirect()->away($targetUrl);
                }
            }
        }

        // ================================================================
        // SUBDOMAIN LOGIC (demo.general-station.com, clinic.general-station.com, etc.)
        // ================================================================
        if ($subdomain) {
            // Super admin should not be on subdomains - redirect to main domain admin
            if ($request->user() && $request->user()->isSuperAdmin()) {
                return redirect()->away('https://'.$appDomain.'/admin/dashboard');
            }

            // If user visits subdomain root, redirect appropriately
            if ($path === '/' || $path === '') {
                if ($request->user()) {
                    // Authenticated: go to dashboard
                    return redirect()->route('dashboard');
                } else {
                    // Guest: go to login
                    return redirect()->route('login');
                }
            }
        }

        return $next($request);
    }

    /**
     * Extract subdomain from host
     */
    private function extractSubdomain(string $host, string $appDomain): ?string
    {
        // Remove port if present
        $host = explode(':', $host)[0];

        // If host equals app domain, no subdomain
        if ($host === $appDomain) {
            return null;
        }

        // Check if host ends with app domain
        if (! str_ends_with($host, '.'.$appDomain)) {
            return null;
        }

        // Extract subdomain
        $subdomain = substr($host, 0, strlen($host) - strlen('.'.$appDomain));

        // Don't treat www as a subdomain
        if ($subdomain === 'www') {
            return null;
        }

        return $subdomain;
    }

    /**
     * Check if we should skip redirect for this request
     */
    private function shouldSkipRedirect(Request $request): bool
    {
        $path = $request->path();

        // Skip for API routes
        if (str_starts_with($path, 'api/')) {
            return true;
        }

        // Skip for 2FA verification and email verification
        if (str_starts_with($path, 'login/verify') ||
            str_starts_with($path, 'verify-email') ||
            str_starts_with($path, 'email/verify')) {
            return true;
        }

        // Skip for password reset routes
        if (str_starts_with($path, 'password') ||
            str_starts_with($path, 'forgot-password') ||
            str_starts_with($path, 'reset-password')) {
            return true;
        }

        // Skip for logout
        if (str_starts_with($path, 'logout')) {
            return true;
        }

        return false;
    }
}
