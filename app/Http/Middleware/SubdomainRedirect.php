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
     * This middleware handles subdomain-based routing:
     * - Root domain (general-station.com): Serves landing page and /admin routes
     * - Subdomains (clinic.general-station.com): Redirect to dashboard or login
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $appDomain = config('app.domain', 'general-station.com');

        // Extract subdomain from host
        $subdomain = $this->extractSubdomain($host, $appDomain);

        // If there's a subdomain (not root domain)
        if ($subdomain) {
            // Skip redirect for API routes, auth routes, and asset requests
            if ($this->shouldSkipRedirect($request)) {
                return $next($request);
            }

            // If user is authenticated
            if ($request->user()) {
                // Super admin should not be on subdomains
                if ($request->user()->isSuperAdmin()) {
                    return redirect()->away('https://'.$appDomain.'/admin/dashboard');
                }

                // Regular users: redirect to dashboard if on root of subdomain
                if ($request->path() === '/' || $request->path() === '') {
                    return redirect()->route('dashboard');
                }
            } else {
                // Guest users: redirect to login if on root of subdomain
                if ($request->path() === '/' || $request->path() === '') {
                    return redirect()->route('login');
                }
            }
        } else {
            // Root domain: Only allow landing pages and /admin routes
            // If authenticated regular user is on root domain, redirect to their subdomain
            if ($request->user() && ! $request->user()->isSuperAdmin()) {
                $tenant = $request->user()->tenant;
                if ($tenant && $tenant->subdomain) {
                    $scheme = $request->isSecure() ? 'https' : 'http';
                    $targetUrl = $scheme.'://'.$tenant->subdomain.'.'.$appDomain.'/dashboard';

                    return redirect()->away($targetUrl);
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

        // Skip for auth routes (login, register, password reset, 2fa, etc.)
        if (str_starts_with($path, 'login') ||
            str_starts_with($path, 'register') ||
            str_starts_with($path, 'password') ||
            str_starts_with($path, 'forgot-password') ||
            str_starts_with($path, 'reset-password') ||
            str_starts_with($path, 'verify-email') ||
            str_starts_with($path, 'email/verify')) {
            return true;
        }

        // Skip for dashboard and other protected routes (they're already being accessed)
        if (str_starts_with($path, 'dashboard') ||
            str_starts_with($path, 'patients') ||
            str_starts_with($path, 'dentists') ||
            str_starts_with($path, 'appointments') ||
            str_starts_with($path, 'treatments') ||
            str_starts_with($path, 'invoices') ||
            str_starts_with($path, 'reports') ||
            str_starts_with($path, 'calendar') ||
            str_starts_with($path, 'profile') ||
            str_starts_with($path, 'subscriptions') ||
            str_starts_with($path, 'notifications')) {
            return true;
        }

        // Skip for subscription expired page
        if (str_starts_with($path, 'subscription-expired')) {
            return true;
        }

        return false;
    }
}
