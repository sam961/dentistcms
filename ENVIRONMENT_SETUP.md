# Environment Configuration Guide

This project uses multiple environment files for different deployment stages.

## Environment Files

### 1. `.env` (Local Development)
- **Location**: Root directory
- **Purpose**: Your local development environment
- **Git**: NOT committed (in .gitignore)
- **Usage**: Active when running locally

```bash
# Copy from example and customize
cp .env.example .env
php artisan key:generate
```

### 2. `.env.example` (Template)
- **Location**: Root directory
- **Purpose**: Template showing required variables
- **Git**: Committed to repository
- **Usage**: Reference for new developers

### 3. `.env.staging` (Staging Server)
- **Location**: Root directory (deploy to staging server)
- **Purpose**: Staging environment configuration
- **Git**: NOT committed (in .gitignore)
- **Usage**: Deploy manually to staging server

**Key Differences from Local:**
- `APP_ENV=staging`
- `APP_DEBUG=true` (for testing)
- `APP_URL=https://staging.dentistcms.com`
- `APP_DOMAIN=staging.dentistcms.com`
- `DB_CONNECTION=mysql`
- `SESSION_DRIVER=database`
- `SESSION_SECURE_COOKIE=true`
- Real SMTP mail settings

### 4. `.env.production` (Production Server)
- **Location**: Root directory (deploy to production server)
- **Purpose**: Production environment configuration
- **Git**: NOT committed (in .gitignore)
- **Usage**: Deploy manually to production server

**Key Differences from Staging:**
- `APP_ENV=production`
- `APP_DEBUG=false` (security)
- `APP_URL=https://dentistcms.com`
- `APP_DOMAIN=dentistcms.com`
- `LOG_LEVEL=error`
- `SESSION_ENCRYPT=true`

## Environment Variables Explained

### Application
```env
APP_NAME="Dentist CMS"           # Application name
APP_ENV=local                     # Environment: local, staging, production
APP_KEY=                          # Encryption key (generate with: php artisan key:generate)
APP_DEBUG=true                    # Show debug info (false in production)
APP_URL=http://localhost          # Base URL
APP_DOMAIN=dentistcms.test        # Domain for multi-tenancy
```

### Database
```env
DB_CONNECTION=sqlite              # Database driver: sqlite, mysql
DB_HOST=127.0.0.1                 # Database host
DB_PORT=3306                      # Database port
DB_DATABASE=dentistcms            # Database name
DB_USERNAME=root                  # Database username
DB_PASSWORD=                      # Database password
```

### Session
```env
SESSION_DRIVER=file               # Session storage: file, database, redis
SESSION_DOMAIN=null               # Cookie domain (.dentistcms.com for production)
SESSION_SECURE_COOKIE=false       # HTTPS only (true in production)
```

### Mail
```env
MAIL_MAILER=log                   # Mailer: log, smtp
MAIL_HOST=smtp.gmail.com          # SMTP host
MAIL_PORT=587                     # SMTP port
MAIL_USERNAME=your@email.com      # SMTP username
MAIL_PASSWORD=app-password        # SMTP password
MAIL_ENCRYPTION=tls               # Encryption: tls, ssl
MAIL_FROM_ADDRESS=noreply@...     # Sender email
MAIL_FROM_NAME="Dentist CMS"      # Sender name
```

## Deployment Workflow

### Local Development
```bash
# 1. Clone repository
git clone <repo-url>

# 2. Copy environment file
cp .env.example .env

# 3. Generate app key
php artisan key:generate

# 4. Configure database and mail
# Edit .env file with your settings

# 5. Run migrations
php artisan migrate

# 6. Start development server
php artisan serve
```

### Staging Deployment
```bash
# 1. On staging server, copy staging environment
cp .env.staging .env

# 2. Generate NEW app key for staging
php artisan key:generate

# 3. Update credentials in .env
# - Database credentials
# - Mail credentials
# - Any staging-specific settings

# 4. Run migrations
php artisan migrate --force

# 5. Build assets
npm run build

# 6. Set permissions
chmod -R 755 storage bootstrap/cache
```

### Production Deployment
```bash
# 1. On production server, copy production environment
cp .env.production .env

# 2. Generate NEW app key for production
php artisan key:generate

# 3. Update credentials in .env
# - Database credentials
# - Mail credentials
# - Any production-specific settings

# 4. Run migrations
php artisan migrate --force

# 5. Build assets
npm run build

# 6. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Set permissions
chmod -R 755 storage bootstrap/cache
```

## Security Considerations

### NEVER Commit These Files:
- `.env` (local development)
- `.env.staging` (staging server)
- `.env.production` (production server)
- Any file containing real credentials

### ALWAYS Commit:
- `.env.example` (template without secrets)

### Best Practices:
1. **Unique Keys**: Generate different `APP_KEY` for each environment
2. **Strong Passwords**: Use strong database and mail passwords
3. **Debug Off**: Set `APP_DEBUG=false` in production
4. **HTTPS**: Use `https://` URLs in staging/production
5. **Secure Cookies**: Set `SESSION_SECURE_COOKIE=true` in production
6. **Encrypt Sessions**: Set `SESSION_ENCRYPT=true` in production
7. **Error Logging**: Set `LOG_LEVEL=error` in production

## Gmail SMTP Setup

To use Gmail for sending emails:

1. Enable 2-Step Verification on your Google Account
2. Generate App Password:
   - Go to: https://myaccount.google.com/apppasswords
   - Select "Mail" as app
   - Copy the 16-character password
3. Update `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD="xxxx xxxx xxxx xxxx"
   MAIL_ENCRYPTION=tls
   ```

## Multi-Tenancy Configuration

The `APP_DOMAIN` variable is crucial for multi-tenancy:

- **Local**: `dentistcms.test`
- **Staging**: `staging.dentistcms.com`
- **Production**: `dentistcms.com`

All tenants access the same domain. Tenant identification is handled via user login, not subdomains.

## Troubleshooting

### "No application encryption key has been specified"
```bash
php artisan key:generate
```

### "Base table or view not found"
```bash
php artisan migrate
```

### Emails not sending
- Check `storage/logs/laravel.log` if `MAIL_MAILER=log`
- Verify SMTP credentials if `MAIL_MAILER=smtp`
- Check Gmail App Password is correct

### Session issues
- Clear cache: `php artisan config:clear`
- Check `SESSION_DOMAIN` matches your domain
- Verify `SESSION_SECURE_COOKIE` settings match your HTTP/HTTPS setup
