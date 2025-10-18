<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerifiedOrSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * Verify email is verified OR user is super admin (who can skip verification)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $redirectToRoute = null): Response
    {
        // Allow super admins to skip email verification
        if ($request->user() && $request->user()->isSuperAdmin()) {
            return $next($request);
        }

        // For regular users, check email verification
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
            ! $request->user()->hasVerifiedEmail())) {
            return $request->expectsJson()
                    ? abort(403, 'Your email address is not verified.')
                    : redirect()->route($redirectToRoute ?: 'verification.notice');
        }

        return $next($request);
    }
}
