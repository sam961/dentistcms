# Multi-Tenancy Domain Configuration

This document explains how to configure and manage the dynamic domain system for the Dentist CMS multi-tenancy feature.

## Configuration

### Environment Variable

The base domain is configured in your `.env` file:

```env
# For local development (Valet)
APP_DOMAIN=dentistcms.test

# For production
APP_DOMAIN=dentistcms.com
```

### Quick Switch Between Environments

**Local Development (Valet):**
```bash
# Update .env
APP_DOMAIN=dentistcms.test

# Clear cache
php artisan config:clear
```

**Production:**
```bash
# Update .env
APP_DOMAIN=dentistcms.com

# Clear and cache config
php artisan config:clear
php artisan config:cache
```

## URL Structure

### Super Admin
- **URL Pattern**: `http://admin.{APP_DOMAIN}`
- **Local**: http://admin.dentistcms.test
- **Production**: https://admin.dentistcms.com
- **Dashboard**: `/admin/dashboard`
- **Tenants Management**: `/admin/tenants`

### Tenant/Clinic
- **URL Pattern**: `http://{subdomain}.{APP_DOMAIN}`
- **Local Example**: http://beauty-smile.dentistcms.test
- **Production Example**: https://beauty-smile.dentistcms.com
- **Dashboard**: `/dashboard`
- **Features**: `/patients`, `/appointments`, `/invoices`, etc.

## Code Usage

### In Controllers

```php
// Get the configured domain
$domain = config('app.domain'); // 'dentistcms.test' or 'dentistcms.com'

// Build tenant URL
$url = $tenant->getUrl('/dashboard');
// Returns: http://beauty-smile.dentistcms.test/dashboard
```

### In Blade Views

```blade
<!-- Display tenant domain -->
<a href="{{ $tenant->getUrl() }}">
    {{ $tenant->subdomain }}.{{ config('app.domain') }}
</a>

<!-- Admin dashboard link -->
<a href="http://admin.{{ config('app.domain') }}/admin/dashboard">
    Admin Panel
</a>
```

### Using the TenantHelper Class

```php
use App\Helpers\TenantHelper;

// Get app domain
$domain = TenantHelper::getAppDomain(); // 'dentistcms.test'

// Build tenant URL
$url = TenantHelper::buildTenantUrl('beauty-smile', '/dashboard');
// Returns: http://beauty-smile.dentistcms.test/dashboard

// Build admin URL
$adminUrl = TenantHelper::buildAdminUrl('/tenants');
// Returns: http://admin.dentistcms.test/admin/tenants

// Check if current request is on admin subdomain
if (TenantHelper::isAdminSubdomain()) {
    // Do admin-specific logic
}

// Check if current request is on a tenant subdomain
if (TenantHelper::isTenantSubdomain()) {
    // Do tenant-specific logic
}
```

## Creating New Tenants

When creating a new tenant, the domain will automatically use the configured `APP_DOMAIN`:

```php
use App\Models\Tenant;
use App\Models\Domain;

$tenant = Tenant::create([
    'name' => 'Beauty Smile Clinic',
    'subdomain' => 'beauty-smile',
    // ... other fields
]);

// Create domain record
Domain::create([
    'tenant_id' => $tenant->id,
    'domain' => $tenant->subdomain . '.' . config('app.domain'),
    'is_primary' => true,
]);

// Get the full URL
echo $tenant->getUrl(); // http://beauty-smile.dentistcms.test
```

## Reserved Subdomains

The following subdomains are reserved and cannot be used by tenants (configured in `config/tenancy.php`):

- `admin` - Super admin panel
- `www` - Main website
- `api` - API endpoints
- `mail`, `smtp`, `pop` - Email services
- `cpanel`, `whm` - Hosting control panels
- And more...

## Middleware Configuration

The `IdentifyTenant` middleware automatically detects the tenant from the subdomain using the configured `APP_DOMAIN`. No changes needed when switching domains!

## Testing

```bash
# Test with .test domain
curl -I http://beauty-smile.dentistcms.test/dashboard

# Test admin dashboard
curl -I http://admin.dentistcms.test/admin/dashboard
```

## Deployment Checklist

When deploying to production:

1. ✅ Update `.env`: `APP_DOMAIN=dentistcms.com`
2. ✅ Clear config: `php artisan config:clear`
3. ✅ Cache config: `php artisan config:cache`
4. ✅ Set up DNS records for `*.dentistcms.com`
5. ✅ Configure SSL certificates for wildcard domain
6. ✅ Update tenant domain records in database if needed

## Database Updates

If you need to update existing tenant domains when switching environments:

```php
// Update all tenant domains to new base domain
use App\Models\Domain;

$oldDomain = 'dentistcms.test';
$newDomain = 'dentistcms.com';

Domain::all()->each(function ($domain) use ($oldDomain, $newDomain) {
    $domain->domain = str_replace($oldDomain, $newDomain, $domain->domain);
    $domain->save();
});
```

## Benefits

- ✅ **Single Configuration Point**: Change `APP_DOMAIN` in one place
- ✅ **Environment Agnostic**: Same code works in local, staging, and production
- ✅ **Easy Testing**: Switch between `.test` and `.com` instantly
- ✅ **Type Safe**: Helper methods with proper return types
- ✅ **Maintainable**: No hardcoded domains in code
