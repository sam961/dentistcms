# Staging Environment Setup on Namecheap

This guide explains how to set up `staging.general-station.com` as a staging environment on Namecheap.

## 🎯 Understanding the Setup

Your staging folder needs the **complete Laravel application**, not just an empty folder.

## 📂 Option 1: Separate Staging Environment (Recommended)

This approach gives you a completely independent staging environment.

### A. File Structure

Your Namecheap should have:

```
/home/username/
├── public_html/                    ← Production (general-station.com)
│   ├── .env (production settings)
│   ├── .htaccess
│   ├── index.php
│   ├── app/
│   ├── bootstrap/
│   └── ... (all Laravel files)
│
└── staging/                        ← Staging (staging.general-station.com)
    ├── .env (staging settings)
    ├── .htaccess
    ├── index.php
    ├── app/
    ├── bootstrap/
    └── ... (all Laravel files)
```

### B. Setup Steps

#### 1. Create Subdomain in cPanel

1. Login to Namecheap cPanel
2. Go to "Subdomains"
3. Create subdomain:
   - **Subdomain**: `staging`
   - **Domain**: `general-station.com`
   - **Document Root**: `/home/username/staging` (or auto-created path)
4. Click "Create"

#### 2. Upload Laravel Files to Staging Folder

**Method 1: Copy from Production**

Via SSH:
```bash
# Copy everything from production to staging
cp -r ~/public_html ~/staging

# Or if staging folder already exists
cp -r ~/public_html/* ~/staging/
```

**Method 2: Git Clone**

Via SSH:
```bash
cd ~/staging
git clone https://github.com/sam961/dentistcms.git .
```

**Method 3: Upload via cPanel File Manager**
1. Compress your project: `zip -r dentist_cms.zip . -x "node_modules/*" ".git/*"`
2. Upload to `/home/username/staging/`
3. Extract

#### 3. Move public/ Contents to Root

In staging folder, Laravel's `public/` folder contents need to be moved to root:

Via SSH:
```bash
cd ~/staging

# Move public folder contents to root
mv public/.htaccess .
mv public/index.php .

# Keep public folder for assets
# public/ folder should stay for CSS/JS/images
```

#### 4. Update index.php Path

Edit `~/staging/index.php`:

Find these lines:
```php
require __DIR__.'/vendor/autoload.php';
```

And:
```php
(require_once __DIR__.'/bootstrap/app.php')
```

Make sure they're pointing to the correct paths (should work as-is if structure is correct).

#### 5. Create Staging .env File

Create `~/staging/.env`:

```env
APP_NAME="Dental CMS - Staging"
APP_ENV=staging
APP_KEY=base64:YOUR_DIFFERENT_KEY_HERE
APP_DEBUG=true
APP_URL=https://staging.general-station.com

# Your GoDaddy domain
APP_DOMAIN=general-station.com

# Staging Database (create separate database!)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=username_staging_db
DB_USERNAME=username_staging_user
DB_PASSWORD=staging_password

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=525600
SESSION_DOMAIN=.general-station.com
SESSION_SECURE_COOKIE=true

# Cache & Queue
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
CACHE_STORE=database

# Mail (same as production or use Mailtrap for testing)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="staging@general-station.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Important:**
- Use `APP_ENV=staging` (not production)
- Use `APP_DEBUG=true` (for debugging)
- **Create a SEPARATE database** for staging

#### 6. Create Staging Database

In cPanel → MySQL Databases:
1. Create database: `username_staging_db`
2. Create user: `username_staging_user`
3. Set password
4. Add user to database with ALL PRIVILEGES

#### 7. Install Dependencies & Run Migrations

Via SSH:
```bash
cd ~/staging

# Generate unique app key for staging
php artisan key:generate

# Install composer dependencies
composer install --optimize-autoloader

# Run migrations
php artisan migrate --force

# Set permissions
chmod -R 755 storage bootstrap/cache

# Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 8. Create Staging Admin

```bash
cd ~/staging
php artisan setup:superadmin
```

Enter staging admin credentials (different from production).

#### 9. Optional: Setup Staging Demo

```bash
cd ~/staging
php artisan setup:demo
```

This creates demo account at: `demo.general-station.com`

**Note:** Demo will work on both production and staging since it's subdomain-based.

## 📂 Option 2: Symlink Approach (Share Code, Different Config)

This approach shares the same codebase but uses different `.env` files.

### Setup

Via SSH:
```bash
# Create staging folder
mkdir ~/staging

# Symlink all Laravel folders except storage
cd ~/staging
ln -s ~/public_html/app app
ln -s ~/public_html/bootstrap bootstrap
ln -s ~/public_html/config config
ln -s ~/public_html/database database
ln -s ~/public_html/public public
ln -s ~/public_html/resources resources
ln -s ~/public_html/routes routes
ln -s ~/public_html/vendor vendor
ln -s ~/public_html/artisan artisan

# Copy these (don't symlink)
cp ~/public_html/.htaccess .
cp ~/public_html/index.php .

# Create separate storage for staging
cp -r ~/public_html/storage storage

# Create staging .env
cp ~/public_html/.env .env
# Edit .env with staging settings
```

**Pros:** Saves disk space, always in sync with production code
**Cons:** Code changes affect both environments

## 🌐 DNS Configuration at GoDaddy

Make sure you have this A record at GoDaddy:

```
Type   Host     Value (Namecheap IP)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
A      staging  123.45.67.89
```

Or use wildcard (if you have `*` A record, staging will automatically work).

## ✅ Verify Staging Setup

1. **Check DNS**: 
   ```bash
   nslookup staging.general-station.com
   # Should show Namecheap IP
   ```

2. **Access staging**:
   ```
   https://staging.general-station.com
   ```

3. **Check it's staging**:
   - Should show "Dental CMS - Staging" in title
   - Look for staging database data (not production)

4. **Test admin login**:
   ```
   https://staging.general-station.com/admin
   ```

## 🔒 Protect Staging with Password (Optional)

To prevent public access to staging:

Create `~/staging/.htaccess` (or add to existing):

```apache
# Basic Auth for Staging
AuthType Basic
AuthName "Staging Environment"
AuthUserFile /home/username/staging/.htpasswd
Require valid-user

# Laravel Routes (keep existing Laravel .htaccess below)
<IfModule mod_rewrite.c>
    # ... existing Laravel rules ...
</IfModule>
```

Create password file via SSH:
```bash
cd ~/staging
htpasswd -c .htpasswd staginguser
# Enter password when prompted
```

Now staging will require username/password before accessing.

## 🔄 Syncing Staging with Production

### Pull Latest Code from Git

```bash
cd ~/staging
git pull origin main

# Update dependencies
composer install --optimize-autoloader
npm install && npm run build

# Run new migrations
php artisan migrate --force

# Clear caches
php artisan optimize:clear
php artisan config:cache
```

### Copy Production Database to Staging (Testing)

**Warning:** This will overwrite staging database!

```bash
# Dump production database
mysqldump -u prod_user -p prod_database > production_dump.sql

# Import to staging
mysql -u staging_user -p staging_database < production_dump.sql

# Clear caches on staging
cd ~/staging
php artisan config:cache
```

## 📋 Staging Checklist

After setup, verify:

- [ ] Staging subdomain created in cPanel
- [ ] All Laravel files in staging folder
- [ ] .htaccess and index.php in staging root
- [ ] Separate .env file with staging settings
- [ ] Separate staging database created
- [ ] Dependencies installed (vendor/ folder)
- [ ] Migrations run successfully
- [ ] File permissions set (755 for storage)
- [ ] Staging admin account created
- [ ] DNS A record for staging exists
- [ ] Can access https://staging.general-station.com
- [ ] Shows "staging" environment in app

## 🚨 Common Issues

### Issue: "Default page" or "cPanel default page"

**Cause:** No index.php or .htaccess in staging folder

**Solution:**
```bash
cd ~/staging
# Make sure these exist:
ls -la index.php .htaccess

# If missing, copy from public folder:
cp public/index.php .
cp public/.htaccess .
```

### Issue: "No input file specified"

**Cause:** index.php paths are wrong

**Solution:**
Edit `~/staging/index.php` and verify paths are relative:
```php
require __DIR__.'/vendor/autoload.php';
```

### Issue: Staging shows production data

**Cause:** Using production database

**Solution:**
Check `~/staging/.env` and verify DB_DATABASE is different from production.

### Issue: 500 error on staging

**Cause:** Permissions or missing .env

**Solution:**
```bash
cd ~/staging
chmod -R 755 storage bootstrap/cache
php artisan config:cache
```

## 📝 Quick Setup Script

Copy and run this via SSH:

```bash
#!/bin/bash

# Quick staging setup
cd ~
cp -r public_html staging
cd staging

# Create staging .env
cp .env .env.backup
sed -i 's/APP_ENV=production/APP_ENV=staging/' .env
sed -i 's/APP_DEBUG=false/APP_DEBUG=true/' .env
sed -i 's|APP_URL=https://general-station.com|APP_URL=https://staging.general-station.com|' .env
sed -i 's/APP_NAME="Dental CMS"/APP_NAME="Dental CMS - Staging"/' .env

# Generate new key
php artisan key:generate

# Set permissions
chmod -R 755 storage bootstrap/cache

echo "✅ Staging setup complete!"
echo "📝 Next steps:"
echo "1. Create staging database in cPanel"
echo "2. Update .env with staging database credentials"
echo "3. Run: php artisan migrate --force"
echo "4. Run: php artisan setup:superadmin"
```

## 📚 Recommended Workflow

1. **Develop** → Local machine
2. **Test** → Push to staging.general-station.com
3. **Deploy** → Push to general-station.com (production)

This ensures you never break production with untested code!
