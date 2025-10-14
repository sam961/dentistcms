<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'super_admin' => \App\Http\Middleware\EnsureSuperAdmin::class,
            'tenant_user' => \App\Http\Middleware\EnsureTenantUser::class,
            'identify_tenant' => \App\Http\Middleware\IdentifyTenant::class,
            'check_subscription' => \App\Http\Middleware\CheckSubscriptionStatus::class,
        ]);

        // Apply tenant identification to all web requests
        $middleware->web(append: [
            \App\Http\Middleware\IdentifyTenant::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Log exceptions to database for super admin monitoring
        $exceptions->report(function (Throwable $e) {
            try {
                // Skip logging for certain exceptions
                $skipExceptions = [
                    \Illuminate\Auth\AuthenticationException::class,
                    \Illuminate\Validation\ValidationException::class,
                    \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
                ];

                if (in_array(get_class($e), $skipExceptions)) {
                    return;
                }

                // Get tenant and user context
                $tenant = app(\App\Services\TenantContext::class)->getTenant();
                $user = auth()->user();

                // Determine error level
                $level = 'error';
                if ($e instanceof \ErrorException) {
                    $level = $e->getSeverity() === E_ERROR ? 'critical' : 'error';
                } elseif (method_exists($e, 'getStatusCode') && $e->getStatusCode() >= 500) {
                    $level = 'critical';
                }

                // Sanitize request input (remove passwords, tokens, etc.)
                $input = request()->except(['password', 'password_confirmation', '_token', 'api_token']);

                // Create error log
                \App\Models\ErrorLog::create([
                    'tenant_id' => $tenant?->id,
                    'user_id' => $user?->id,
                    'level' => $level,
                    'type' => class_basename($e),
                    'message' => $e->getMessage(),
                    'exception' => get_class($e),
                    'trace' => $e->getTraceAsString(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                    'input' => json_encode($input),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'status' => 'new',
                ]);
            } catch (\Exception $logException) {
                // Fail silently - don't break the app if logging fails
                \Log::error('Failed to log error to database: ' . $logException->getMessage());
            }
        });
    })
    ->create();
