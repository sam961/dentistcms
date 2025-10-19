# Deployment Guide - Dental CMS

This guide explains how to deploy the Dental CMS to production and set up required accounts.

## 📋 Prerequisites

- PHP 8.4+
- Composer
- MySQL/PostgreSQL (production) or SQLite (testing)
- Node.js & NPM
- SSH access to your server

## 🚀 Initial Deployment Steps

### 1. Clone and Install Dependencies

```bash
# Clone repository
git clone <your-repo-url> dentist_cms
cd dentist_cms

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies and build assets
npm install
npm run build

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 2. Configure Environment

Edit `.env` file with your production settings:

```env
APP_NAME="Dental CMS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dentist_cms
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Email configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Run Migrations

```bash
# Run all migrations
php artisan migrate --force

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Set File Permissions

```bash
# Set correct permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 👤 Setup Super Admin (Required)

After deployment, create your super admin account via SSH:

```bash
# Interactive mode (recommended for first use)
php artisan setup:superadmin

# Non-interactive mode (for scripts)
php artisan setup:superadmin \
  --email=admin@yourdomain.com \
  --password=YourSecurePassword123 \
  --name="System Administrator"
```

**What it does:**
- Creates a super admin user with full system access
- Sets up email verification automatically
- Provides access to `/admin` panel
- Checks for existing super admins to prevent duplicates

**Super Admin Login:**
- URL: `https://yourdomain.com/admin/login`
- Email: (the one you provided)
- Password: (the one you provided)

## 🎭 Setup Demo Account (Optional)

If you want to provide a demo for potential customers:

```bash
# Interactive mode
php artisan setup:demo

# Force fresh setup (deletes existing demo)
php artisan setup:demo --fresh
```

**What it does:**
- Creates demo tenant with subdomain "demo"
- Creates demo admin user with static credentials:
  - Email: demo@dentistcms.com
  - Password: demo123456
  - 2FA Code: 123456
- Seeds 30 patients, 3 dentists, 5 treatments, 60 appointments
- Enables automatic hourly data reset

**Demo Account Access:**
- URL: `demo.yourdomain.com`
- Email: `demo@dentistcms.com`
- Password: `demo123456`
- 2FA Code: `123456`

## ⏰ Setup Cron for Scheduled Tasks

Add to your server's crontab (`crontab -e`):

```bash
* * * * * cd /path/to/dentist_cms && php artisan schedule:run >> /dev/null 2>&1
```

**Scheduled Tasks:**
- **Every 15 minutes**: Update past appointments status
- **Every hour**: Reset demo data (only if demo exists)

## 🔒 Security Checklist

- [ ] Set `APP_DEBUG=false` in production
- [ ] Use strong database credentials
- [ ] Configure HTTPS/SSL certificate
- [ ] Set up firewall rules
- [ ] Configure backup strategy
- [ ] Enable fail2ban or similar
- [ ] Set up monitoring and alerts
- [ ] Restrict file permissions (775 for storage, 644 for files)

## 📊 Post-Deployment Verification

```bash
# Check application status
php artisan about

# Verify scheduled tasks
php artisan schedule:list

# Test database connection
php artisan tinker --execute "DB::connection()->getPdo();"

# Check routes
php artisan route:list
```

## 🔄 Updates and Maintenance

```bash
# Pull latest changes
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Run migrations
php artisan migrate --force

# Clear caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart queue workers (if using)
php artisan queue:restart
```

## 🆘 Troubleshooting

### Can't login to super admin panel
```bash
# Verify super admin exists
php artisan tinker --execute "User::where('is_super_admin', true)->get(['email', 'name']);"

# Reset super admin password
php artisan tinker
>>> $admin = User::where('email', 'admin@example.com')->first();
>>> $admin->password = Hash::make('NewPassword123');
>>> $admin->save();
```

### Demo account not working
```bash
# Recreate demo
php artisan setup:demo --fresh

# Manually reset demo data
php artisan demo:reset --force
```

### Scheduler not running
```bash
# Test scheduler manually
php artisan schedule:run

# Check cron logs
grep CRON /var/log/syslog

# Verify crontab
crontab -l
```

## 📝 Command Reference

| Command | Description |
|---------|-------------|
| `php artisan setup:superadmin` | Create super admin user |
| `php artisan setup:demo` | Setup demo account with data |
| `php artisan demo:reset --force` | Manually reset demo data |
| `php artisan schedule:list` | List all scheduled tasks |
| `php artisan about` | Display application info |

## 🌐 DNS Configuration

For multi-tenant setup with subdomains:

```
# Main domain
A     @           your-server-ip
A     www         your-server-ip

# Demo subdomain
A     demo        your-server-ip

# Wildcard for tenant subdomains
A     *           your-server-ip
```

## 📧 Support

For deployment issues or questions:
- Check logs: `storage/logs/laravel.log`
- Review error logs from web server
- Contact: your-support-email@example.com
