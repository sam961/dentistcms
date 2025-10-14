<?php

namespace App\Helpers;

class TenantHelper
{
    /**
     * Get the configured app domain (e.g., dentistcms.test or dentistcms.com)
     */
    public static function getAppDomain(): string
    {
        return config('app.domain', 'dentistcms.test');
    }

    /**
     * Build a tenant URL with subdomain
     *
     * @param string $subdomain The tenant subdomain (e.g., 'beauty-smile')
     * @param string|null $path Optional path to append (e.g., '/dashboard')
     * @param bool $secure Whether to use HTTPS
     * @return string The full URL
     */
    public static function buildTenantUrl(string $subdomain, ?string $path = null, bool $secure = false): string
    {
        $protocol = $secure ? 'https' : 'http';
        $domain = self::getAppDomain();
        $url = "{$protocol}://{$subdomain}.{$domain}";

        if ($path) {
            $url .= '/' . ltrim($path, '/');
        }

        return $url;
    }

    /**
     * Build admin URL
     *
     * @param string|null $path Optional path to append (e.g., '/dashboard')
     * @param bool $secure Whether to use HTTPS
     * @return string The full URL
     */
    public static function buildAdminUrl(?string $path = null, bool $secure = false): string
    {
        return self::buildTenantUrl('admin', $path, $secure);
    }

    /**
     * Get the base domain without subdomain
     *
     * @return string
     */
    public static function getBaseDomain(): string
    {
        return self::getAppDomain();
    }

    /**
     * Extract subdomain from a domain string
     *
     * @param string $domain Full domain (e.g., 'beauty-smile.dentistcms.test')
     * @return string|null The subdomain or null
     */
    public static function extractSubdomain(string $domain): ?string
    {
        $baseDomain = self::getAppDomain();

        if (str_ends_with($domain, ".{$baseDomain}")) {
            $subdomain = str_replace(".{$baseDomain}", '', $domain);
            return $subdomain ?: null;
        }

        return null;
    }

    /**
     * Check if current request is on admin subdomain
     *
     * @return bool
     */
    public static function isAdminSubdomain(): bool
    {
        $host = request()->getHost();
        $subdomain = self::extractSubdomain($host);

        return $subdomain === 'admin';
    }

    /**
     * Check if current request is on a tenant subdomain
     *
     * @return bool
     */
    public static function isTenantSubdomain(): bool
    {
        $host = request()->getHost();
        $subdomain = self::extractSubdomain($host);

        return $subdomain && $subdomain !== 'admin' && $subdomain !== 'www';
    }
}
