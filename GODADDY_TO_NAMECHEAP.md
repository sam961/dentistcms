# GoDaddy Domain → Namecheap Hosting Deployment Guide

This guide is for deploying Dental CMS when your **domain is registered at GoDaddy** but **hosting is at Namecheap**.

## 🌐 Step 1: Point GoDaddy Domain to Namecheap Hosting

### A. Get Namecheap Server IP Address

1. Login to **Namecheap cPanel**
2. Look for "Server Information" or "Shared IP Address"
3. Copy your server IP (e.g., `123.45.67.89`)

OR

4. In Namecheap cPanel → "Domains" → Find your shared IP address

### B. Configure DNS at GoDaddy

1. Login to **GoDaddy.com**
2. Go to "My Products" → "Domains"
3. Click on your domain → "DNS" button (or "Manage DNS")
4. **Delete any existing A records** for @ and www

5. **Add these A Records:**

```
Type    Name/Host    Value (Points to)              TTL
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
A       @            NAMECHEAP_SERVER_IP            1 Hour
A       www          NAMECHEAP_SERVER_IP            1 Hour
A       demo         NAMECHEAP_SERVER_IP            1 Hour
A       *            NAMECHEAP_SERVER_IP            1 Hour ⭐ WILDCARD
```

**Example:**
```
Type    Name    Value           TTL
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
A       @       123.45.67.89    1 Hour
A       www     123.45.67.89    1 Hour
A       demo    123.45.67.89    1 Hour
A       *       123.45.67.89    1 Hour
```

**Important Notes:**
- `@` means root domain (yourdomain.com)
- `www` is for www.yourdomain.com
- `demo` is for demo.yourdomain.com
- `*` (wildcard) enables ALL subdomains (clinic1.yourdomain.com, etc.)

6. **Remove/Disable:**
   - Any CNAME records pointing to GoDaddy parking
   - Any Nameserver settings (should be "Custom" not "Parked")

7. **Save Changes**

⏱️ **DNS Propagation:** Changes take 30 minutes to 48 hours (usually 1-2 hours)

### C. Verify DNS Propagation

Check if DNS is working:

```bash
# On your computer terminal/command prompt
nslookup yourdomain.com
ping yourdomain.com
ping demo.yourdomain.com
```

You should see your Namecheap server IP address.

**Online Tools:**
- https://www.whatsmydns.net/ (check global DNS propagation)
- Enter your domain and select "A" record type

## 📂 Step 2: Setup Domain in Namecheap cPanel

### A. Add Domain (if not already added)

1. Login to **Namecheap cPanel**
2. Go to "Addon Domains" or "Domains"
3. Add your GoDaddy domain:
   - Domain: `yourdomain.com`
   - Document Root: `public_html` (or create new folder)

### B. Setup Wildcard Subdomain in cPanel

Some Namecheap plans require this:

1. In cPanel → "Subdomains"
2. Create subdomain:
   - Subdomain: `*` (asterisk)
   - Domain: `yourdomain.com`
   - Document Root: Same as main domain (`public_html`)

## 📤 Step 3: Upload Laravel Files to Namecheap

### A. File Structure in public_html

```
public_html/
├── .env
├── .htaccess (moved from public/ folder)
├── index.php (moved from public/ folder)
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

**CRITICAL:** 
- Move `public/.htaccess` to `public_html/.htaccess`
- Move `public/index.php` to `public_html/index.php`
- Keep `public/` folder for assets

### B. Upload Methods

**Option 1: Git (Recommended)**
```bash
# In Namecheap cPanel → Git Version Control
Clone URL: https://github.com/yourusername/dentistcms.git
Path: /home/USERNAME/repositories/dentist_cms
```

Then create symlink or copy files to public_html.

**Option 2: FTP/SFTP**
- Use FileZilla or Cyberduck
- Host: Your Namecheap server
- Upload all files to `/home/USERNAME/public_html/`

**Option 3: Zip Upload**
```bash
# On your computer
zip -r dentist_cms.zip . -x "node_modules/*" ".git/*" "vendor/*"

# Upload via cPanel File Manager
# Extract in public_html
```

## ⚙️ Step 4: Configure .env for Production

Create/Edit `.env` file in `public_html`:

```env
APP_NAME="Dental CMS"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

# CRITICAL: Your GoDaddy domain name
APP_DOMAIN=yourdomain.com

# Database from Namecheap cPanel
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_namecheap_db_name
DB_USERNAME=your_namecheap_db_user
DB_PASSWORD=your_namecheap_db_password

# Session - CRITICAL for subdomains
SESSION_DRIVER=database
SESSION_LIFETIME=525600
SESSION_DOMAIN=.yourdomain.com
SESSION_SECURE_COOKIE=true

# Cache & Queue
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
CACHE_STORE=database

# Mail (GoDaddy email or any SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.secureserver.net
MAIL_PORT=465
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Key Settings:**
- `APP_DOMAIN=yourdomain.com` - Your GoDaddy domain
- `SESSION_DOMAIN=.yourdomain.com` - Leading dot for subdomains!
- `DB_*` - Database from Namecheap cPanel

## 🗄️ Step 5: Database Setup (Namecheap cPanel)

1. cPanel → "MySQL Databases"
2. Create Database: `username_dentist`
3. Create User: `username_dentist`
4. Add user to database with ALL PRIVILEGES
5. Update `.env` with these credentials

## 🚀 Step 6: Run Setup Commands (SSH Terminal)

```bash
# Login to SSH (Terminal in Namecheap cPanel)
cd ~/public_html

# Generate app key
php artisan key:generate

# Install dependencies (if needed)
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Set permissions
chmod -R 755 storage bootstrap/cache
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type f -exec chmod 644 {} \;

# Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 👤 Step 7: Create Super Admin

```bash
cd ~/public_html
php artisan setup:superadmin
```

Follow prompts to create your admin account.

**Login URL:** `https://yourdomain.com/admin`

## 🎭 Step 8: Setup Demo Account (Optional)

```bash
cd ~/public_html
php artisan setup:demo
```

**Demo Access:**
- URL: `https://demo.yourdomain.com`
- Email: `demo@dentistcms.com`
- Password: `demo123456`
- 2FA Code: `123456`

## ⏰ Step 9: Setup Cron Job (Namecheap cPanel)

cPanel → "Cron Jobs" → Add:

```
* * * * * cd /home/USERNAME/public_html && php artisan schedule:run >> /dev/null 2>&1
```

Replace `USERNAME` with your Namecheap username.

## 🔒 Step 10: SSL Certificate Setup

### Option A: Let's Encrypt via Namecheap cPanel

1. cPanel → "SSL/TLS Status"
2. Select all domains (main + wildcard)
3. Click "Run AutoSSL"
4. Wait 5-10 minutes

### Option B: Use GoDaddy SSL (if purchased)

1. In GoDaddy → Generate SSL certificate
2. Download certificate files
3. In Namecheap cPanel → "SSL/TLS" → "Manage SSL Sites"
4. Upload:
   - Certificate (CRT)
   - Private Key
   - CA Bundle (if provided)

### Force HTTPS in .htaccess

Add to `public_html/.htaccess` (after `RewriteEngine On`):

```apache
# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

## 🎨 Step 11: Build and Upload Assets

**On your local machine:**

```bash
npm install
npm run build
```

**Upload via FTP/File Manager:**
- Upload `public/build/` folder to `public_html/public/build/`

## ✅ Step 12: Testing Checklist

Test these URLs:

- [ ] `https://yourdomain.com` - Main site
- [ ] `https://www.yourdomain.com` - WWW redirect
- [ ] `https://yourdomain.com/admin` - Super admin login
- [ ] `https://demo.yourdomain.com` - Demo account
- [ ] Create test tenant with subdomain `clinic1`
- [ ] `https://clinic1.yourdomain.com` - Test tenant

## 🔍 Common Issues & Solutions

### Issue 1: Domain still showing GoDaddy parking page

**Solution:**
1. Wait longer (DNS can take up to 48 hours)
2. Clear browser cache
3. Try incognito/private browsing
4. Verify GoDaddy A records point to correct IP
5. Check `nslookup yourdomain.com` shows Namecheap IP

### Issue 2: Main domain works, subdomains show 404

**Solution:**
1. Verify wildcard `*` A record at GoDaddy
2. Check `.htaccess` is in `public_html/`
3. Verify `SESSION_DOMAIN=.yourdomain.com` in `.env`
4. Create wildcard subdomain in Namecheap cPanel
5. Clear Laravel cache: `php artisan config:cache`

### Issue 3: SSL certificate not working

**Solution:**
1. Run AutoSSL again in cPanel
2. Make sure domain is fully propagated first
3. Check both main and wildcard SSL coverage
4. Force browser to refresh (Ctrl+F5)

### Issue 4: Session issues across subdomains

**Solution:**
Update `.env`:
```env
SESSION_DRIVER=database
SESSION_DOMAIN=.yourdomain.com
SESSION_SECURE_COOKIE=true
```

Then:
```bash
php artisan config:cache
```

### Issue 5: Email not sending from GoDaddy domain

**GoDaddy Email Settings:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.secureserver.net
MAIL_PORT=465
MAIL_USERNAME=your-email@yourdomain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=ssl
```

For Office 365 (if using):
```env
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
```

## 📊 DNS Verification Commands

Check if everything is configured correctly:

```bash
# Check A record
nslookup yourdomain.com

# Check wildcard
nslookup random.yourdomain.com

# Check demo subdomain
nslookup demo.yourdomain.com

# Trace route
traceroute yourdomain.com
```

All should point to your Namecheap server IP.

## 📝 Summary

**What You Have:**
- Domain registered at: **GoDaddy** ✅
- Hosting at: **Namecheap** ✅
- DNS managed at: **GoDaddy** (pointing to Namecheap) ✅

**Key Configuration:**
1. GoDaddy DNS: A records → Namecheap IP
2. Namecheap: Host the files
3. Laravel .env: APP_DOMAIN = your GoDaddy domain

**This setup works perfectly and is very common!**

## 🆘 Need Help?

1. **Check DNS propagation:** https://www.whatsmydns.net/
2. **Verify Namecheap IP:** cPanel → Server Information
3. **Check Laravel logs:** `storage/logs/laravel.log`
4. **cPanel error logs:** cPanel → Errors section

## 📧 Support Resources

- **GoDaddy DNS Help:** GoDaddy support (for DNS/A records)
- **Namecheap Hosting:** Namecheap support (for server/cPanel)
- **Laravel Logs:** Check `storage/logs/laravel.log` on server
