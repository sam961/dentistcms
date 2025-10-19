# Namecheap Deployment Guide - Dental CMS with Subdomains

This guide explains how to deploy your Dental CMS to Namecheap hosting with multi-tenant subdomain support.

## 🌐 Step 1: DNS Configuration (Namecheap Dashboard)

### A. Setup Wildcard Subdomain DNS

1. Login to Namecheap Dashboard
2. Go to "Domain List" → Select your domain (e.g., `yourdomain.com`)
3. Click "Manage" → Go to "Advanced DNS" tab
4. Add these DNS records:

```
Type    Host    Value                   TTL
A       @       YOUR_SERVER_IP          Automatic
A       www     YOUR_SERVER_IP          Automatic
A       demo    YOUR_SERVER_IP          Automatic
A       *       YOUR_SERVER_IP          Automatic (wildcard for all subdomains)
```

**Important Notes:**
- The `*` (wildcard) record enables unlimited subdomains (e.g., clinic1.yourdomain.com, clinic2.yourdomain.com)
- `demo` is specifically for the demo account
- DNS changes can take 5-30 minutes to propagate

## 📂 Step 2: File Upload to Namecheap cPanel

### A. Access cPanel File Manager

1. Login to Namecheap cPanel
2. Go to "File Manager"
3. Navigate to `public_html` directory

### B. Upload Your Laravel Files

**Option 1: Using File Manager (Small projects)**
1. Compress your project locally: `zip -r dentist_cms.zip . -x "node_modules/*" ".git/*"`
2. Upload `dentist_cms.zip` via File Manager
3. Extract in cPanel File Manager

**Option 2: Using Git (Recommended)**
1. In cPanel, go to "Git Version Control"
2. Click "Create" and enter:
   - Clone URL: `https://github.com/yourusername/dentistcms.git`
   - Repository Path: `/home/yourusername/repositories/dentist_cms`
   - Branch: `main`
3. Click "Create"

**Option 3: Using SSH/FTP (Most Control)**
```bash
# Via SFTP (FileZilla, Cyberduck, etc.)
# Upload all files to: /home/yourusername/public_html/
```

### C. Correct File Structure

Your `public_html` should look like this:

```
public_html/
├── .env
├── .htaccess (from public folder)
├── index.php (from public folder)
├── app/
├── bootstrap/
├── config/
├── database/
├── public/ (keep for assets)
├── resources/
├── routes/
├── storage/
├── vendor/
└── artisan
```

**IMPORTANT:** Move everything from `public/` folder to `public_html/`, including `.htaccess` and `index.php`

## ⚙️ Step 3: Environment Configuration

### A. Create .env File

In cPanel File Manager, create `.env` file in `public_html`:

```env
APP_NAME="Dental CMS"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

# CRITICAL: Set your main domain
APP_DOMAIN=yourdomain.com

# Database (MySQL from cPanel)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Session (Important for subdomains)
SESSION_DRIVER=database
SESSION_LIFETIME=525600
SESSION_DOMAIN=.yourdomain.com
SESSION_SECURE_COOKIE=true

# Cache & Queue
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
CACHE_STORE=database

# Mail Configuration (Use your email service)
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Key Settings for Subdomains:**
- `APP_DOMAIN=yourdomain.com` (your actual domain)
- `SESSION_DOMAIN=.yourdomain.com` (note the leading dot for subdomains)
- `SESSION_SECURE_COOKIE=true` (requires HTTPS)

### B. Generate Application Key

Via SSH (Terminal in cPanel):
```bash
cd ~/public_html
php artisan key:generate
```

Or manually generate and add to `.env`:
```bash
# On your local machine
php artisan key:generate --show
# Copy the output to .env APP_KEY
```

## 🗄️ Step 4: Database Setup

### A. Create Database in cPanel

1. Go to cPanel → "MySQL Databases"
2. Create database: e.g., `yourusername_dentist`
3. Create database user: e.g., `yourusername_dentist`
4. Set a strong password
5. Add user to database with "ALL PRIVILEGES"
6. Note down these credentials for `.env`

### B. Run Migrations

Via SSH:
```bash
cd ~/public_html
php artisan migrate --force
```

## 🔧 Step 5: File Permissions

Set correct permissions via SSH or File Manager:

```bash
cd ~/public_html

# Set directory permissions
chmod -R 755 storage bootstrap/cache

# Set file permissions
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type f -exec chmod 644 {} \;

# Make sure .env is not publicly accessible
chmod 644 .env
```

## 🌍 Step 6: Setup Subdomain Routing (.htaccess)

Your main `.htaccess` in `public_html` should be:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

Update `public_html/index.php`:

```php
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/bootstrap/app.php')
    ->handleRequest(Request::capture());
```

## 👤 Step 7: Create Super Admin

Via SSH Terminal in cPanel:

```bash
cd ~/public_html
php artisan setup:superadmin
```

Follow the prompts to create your admin account.

## 🎭 Step 8: Setup Demo Account (Optional)

```bash
cd ~/public_html
php artisan setup:demo
```

This creates:
- Demo tenant at: `demo.yourdomain.com`
- Credentials: demo@dentistcms.com / demo123456 / 2FA: 123456

## ⏰ Step 9: Setup Cron Jobs

In cPanel → "Cron Jobs":

**Add this cron job:**
```
* * * * * cd /home/yourusername/public_html && php artisan schedule:run >> /dev/null 2>&1
```

Settings:
- Minute: `*`
- Hour: `*`
- Day: `*`
- Month: `*`
- Weekday: `*`
- Command: `cd /home/yourusername/public_html && php artisan schedule:run >> /dev/null 2>&1`

## 🔒 Step 10: Enable SSL (HTTPS)

### A. Install SSL Certificate

1. In cPanel → "SSL/TLS Status"
2. Select "Run AutoSSL" for your domain and all subdomains
3. Wait for certificate installation (5-10 minutes)

OR

1. Go to "SSL/TLS" → "Manage SSL Sites"
2. Namecheap usually provides free SSL (Let's Encrypt)
3. Enable for both main domain and wildcard

### B. Force HTTPS

Add to `.env`:
```env
APP_URL=https://yourdomain.com
SESSION_SECURE_COOKIE=true
```

Update `.htaccess` (add after `RewriteEngine On`):
```apache
# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

## 🎨 Step 11: Build Assets

### Option 1: Build locally and upload

On your local machine:
```bash
npm install
npm run build
```

Upload the `public/build` folder to your server's `public_html/public/build`

### Option 2: Build on server (if Node.js available)

Via SSH:
```bash
cd ~/public_html
npm install
npm run build
```

## ✅ Step 12: Final Verification

### A. Clear All Caches

```bash
cd ~/public_html
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### B. Test Your Subdomains

1. **Main domain:** `https://yourdomain.com`
2. **Demo subdomain:** `https://demo.yourdomain.com`
3. **Admin panel:** `https://yourdomain.com/admin`

### C. Create Test Tenant

1. Go to super admin panel
2. Create a new tenant with subdomain: `clinic1`
3. Test: `https://clinic1.yourdomain.com`

## 🔍 Troubleshooting

### Issue: Subdomains show 404

**Solution:**
1. Verify wildcard DNS record (`*` → your IP)
2. Wait 30 minutes for DNS propagation
3. Check `.htaccess` is correctly placed
4. Clear browser cache

### Issue: "Vite manifest not found"

**Solution:**
```bash
npm run build
# Upload public/build folder
```

### Issue: Database connection failed

**Solution:**
1. Verify database credentials in `.env`
2. Ensure database user has privileges
3. Check `DB_HOST` is `localhost` not `127.0.0.1`

### Issue: 500 Internal Server Error

**Solution:**
```bash
# Check file permissions
chmod -R 755 storage bootstrap/cache

# Check Laravel logs
tail -50 storage/logs/laravel.log
```

### Issue: Session issues across subdomains

**Solution:**
Update `.env`:
```env
SESSION_DOMAIN=.yourdomain.com
SESSION_SECURE_COOKIE=true
```

Clear cache:
```bash
php artisan config:cache
```

## 📝 Post-Deployment Checklist

- [ ] DNS wildcard configured (`*` A record)
- [ ] All files uploaded to `public_html`
- [ ] `.env` configured with production settings
- [ ] Database created and migrated
- [ ] File permissions set (755 for directories, 644 for files)
- [ ] Super admin created
- [ ] SSL certificate installed
- [ ] Cron job configured
- [ ] Assets built and uploaded
- [ ] Caches cleared
- [ ] Demo account tested (if enabled)
- [ ] Test subdomain works

## 🆘 Need Help?

Check Laravel logs:
```bash
tail -100 ~/public_html/storage/logs/laravel.log
```

Check cPanel error logs:
- cPanel → "Errors" → Last 300 errors

## 📧 Support

For deployment issues:
- Check logs first
- Verify all checklist items
- Contact Namecheap support for server/DNS issues
