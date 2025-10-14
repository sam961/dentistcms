# Subdomain Validation System

## Overview

The system now validates ALL subdomains before allowing access. Only valid subdomains created by the super admin can be accessed.

## How It Works

### 1. Global Middleware Protection

The `IdentifyTenant` middleware is now applied **globally** to all web routes via `bootstrap/app.php`. This means every single request goes through subdomain validation.

### 2. Three Types of Subdomains

#### ✅ Valid Tenant Subdomains
- **Must exist** in the `domains` table
- **Must be active** (tenant status = 'active')
- Examples: `beauty-smile`, `salam-clinic`, `dental-care`
- Created by super admin through tenant management

#### ✅ Reserved Subdomains
- **Predefined system subdomains** that bypass tenant validation
- Cannot be used by tenants
- List defined in `config/tenancy.php`:
  - `admin` - Super admin panel
  - `www` - Main website
  - `api` - API endpoints
  - `mail`, `smtp`, `pop` - Email services
  - `cpanel`, `whm` - Hosting control panels
  - And more...

#### ❌ Invalid Subdomains
- **Any other subdomain** not in the above categories
- Returns **404 Not Found** error
- Error message: "Clinic not found. The subdomain '{name}' is not registered."

## Testing Results

```bash
# ❌ Invalid subdomain - Returns 404
curl -I http://nonexistent-clinic.dentistcms.test/login
# HTTP/1.1 404 Not Found

# ✅ Valid tenant subdomain - Returns 200
curl -I http://beauty-smile.dentistcms.test/login
# HTTP/1.1 200 OK

# ✅ Reserved subdomain (admin) - Returns 200
curl -I http://admin.dentistcms.test/login
# HTTP/1.1 200 OK
```

## Security Benefits

1. ✅ **Prevents subdomain squatting** - Users can't access arbitrary subdomains
2. ✅ **Protects reserved names** - System subdomains are protected
3. ✅ **Early validation** - Fails fast before hitting authentication
4. ✅ **Clear error messages** - Users know what went wrong
5. ✅ **Database-driven** - Only registered tenants can be accessed

## Code Flow

```
Request: http://my-clinic.dentistcms.test/dashboard
         ↓
    IdentifyTenant Middleware (Global)
         ↓
    Extract subdomain: "my-clinic"
         ↓
    Is it reserved? (admin, www, api, etc.)
         ├─ YES → Allow access (bypass tenant check)
         └─ NO  → Check domains table
                   ├─ Found & Active → Set tenant context, allow access
                   └─ Not Found → Abort 404 "Clinic not found"
```

## Files Modified

1. **[bootstrap/app.php:21-23](bootstrap/app.php#L21-L23)**
   - Added global `IdentifyTenant` middleware to web group

2. **[routes/web.php:32](routes/web.php#L32)**
   - Removed redundant `identify_tenant` from route group (now global)

3. **[app/Http/Middleware/IdentifyTenant.php](app/Http/Middleware/IdentifyTenant.php)**
   - Added `isReservedSubdomain()` method
   - Enhanced error messages
   - Clearer logic flow

4. **[config/tenancy.php](config/tenancy.php)**
   - Defines reserved subdomains list

## Creating Valid Tenants

Only super admins can create valid tenants through the admin panel:

1. Login to admin panel: http://admin.dentistcms.test/admin/dashboard
2. Click "New Client" button
3. Fill in clinic details and subdomain
4. System creates:
   - Tenant record in `tenants` table
   - Domain record in `domains` table (e.g., `clinic-name.dentistcms.test`)
5. Tenant can now access: http://clinic-name.dentistcms.test

## Reserved Subdomains List

The following subdomains are reserved and managed in `config/tenancy.php`:

```php
'reserved_subdomains' => [
    'www',      // Main website
    'admin',    // Super admin panel
    'api',      // API endpoints
    'mail',     // Email service
    'ftp',      // File transfer
    'localhost',// Local development
    'webmail',  // Webmail interface
    'smtp',     // SMTP server
    'pop',      // POP3 server
    'ns1',      // Name server 1
    'ns2',      // Name server 2
    'cpanel',   // Control panel
    'whm',      // Web Host Manager
    'webdisk',  // WebDisk
    'blog',     // Blog/news
    'shop',     // E-commerce
],
```

## Adding New Reserved Subdomains

Edit `config/tenancy.php` and add to the `reserved_subdomains` array:

```php
'reserved_subdomains' => [
    // ... existing ones
    'support',  // Add support subdomain
    'docs',     // Add documentation subdomain
],
```

Then clear config cache:
```bash
php artisan config:clear
php artisan config:cache
```

## Error Messages

### Invalid Subdomain (404)
```
Clinic not found. The subdomain "nonexistent" is not registered.
Please check the URL or contact support.
```

### Inactive Tenant (403)
```
This clinic is currently inactive. Please contact support.
```

### Suspended Tenant (403)
```
This clinic is currently suspended. Please contact support.
```

## Best Practices

1. ✅ Always create tenants through the super admin panel
2. ✅ Use descriptive, professional subdomain names
3. ✅ Avoid using reserved subdomain names for tenants
4. ✅ Test subdomain before giving to client
5. ✅ Keep the reserved subdomains list updated
6. ✅ Monitor 404 errors for invalid subdomain attempts

## Summary

✅ **Problem Solved**: Any subdomain was accessible before
✅ **Solution**: Global middleware validates ALL subdomains
✅ **Result**: Only super admin-created tenants + reserved subdomains work
✅ **Security**: Protected against unauthorized subdomain access
