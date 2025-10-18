# Deployment Guide - Namecheap Shared Hosting

This guide documents how to deploy the Dental Hub CMS to Namecheap shared hosting.

## Prerequisites

- Namecheap shared hosting account with SSH access
- cPanel access for database management
- Git installed on the server

## Server Structure

```
/home/genecdsh/
├── dentistcms/          # Laravel application root
│   ├── app/
│   ├── public/
│   ├── storage/
│   └── ...
└── public_html/         # Symlink to dentistcms/public
```

## Initial Deployment Steps

### 1. Clone Repository

```bash
cd ~
git clone <repository-url> dentistcms
cd dentistcms
```

### 2. Install Dependencies

```bash
composer install --optimize-autoloader --no-dev
```

### 3. Configure Environment

```bash
cp .env.example .env
nano .env
```

Update `.env` with production settings:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://general-station.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=genecdsh_dentalhub
DB_USERNAME=genecdsh_admin
DB_PASSWORD=your_password
```

Generate application key:
```bash
php artisan key:generate
```

### 4. Set Up Database

In cPanel:
1. Create MySQL database: `genecdsh_dentalhub`
2. Create user: `genecdsh_admin` with strong password
3. Assign user to database with ALL PRIVILEGES

Run migrations:
```bash
php artisan migrate --force
php artisan db:seed --force  # Optional: seed sample data
```

### 5. Build Frontend Assets

**On local machine:**
```bash
npm run build
```

Upload `public/build/` folder to server via cPanel File Manager or compress and upload:
```bash
cd public
zip -r build.zip build/
# Upload build.zip via cPanel, then on server:
cd ~/dentistcms/public
unzip build.zip
rm build.zip
```

### 6. Link public_html to Laravel

```bash
cd ~
rm -rf public_html
ln -s ~/dentistcms/public public_html
```

### 7. Set Permissions

```bash
chmod -R 755 ~/dentistcms
chmod -R 775 ~/dentistcms/storage
chmod -R 775 ~/dentistcms/bootstrap/cache
```

### 8. Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Updating Deployment

### Pull Latest Changes

```bash
cd ~/dentistcms
git pull origin main
```

### Update Dependencies

```bash
composer install --optimize-autoloader --no-dev
```

### Run Migrations

```bash
php artisan migrate --force
```

### Rebuild Frontend Assets

Build locally and upload `public/build/` folder as described in step 5 above.

### Clear and Rebuild Caches

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Troubleshooting

### 500 Internal Server Error

1. Check Laravel logs:
   ```bash
   tail -100 ~/dentistcms/storage/logs/laravel.log
   ```

2. Check PHP error logs:
   ```bash
   cat ~/.php.error.log
   ```

3. Enable debug mode temporarily:
   ```bash
   nano .env
   # Set APP_DEBUG=true
   php artisan config:clear
   # Remember to set back to false after debugging!
   ```

### Permission Issues

```bash
chmod -R 775 ~/dentistcms/storage
chmod -R 775 ~/dentistcms/bootstrap/cache
```

### Database Connection Issues

- Verify database credentials in `.env`
- Ensure user has ALL PRIVILEGES on the database in cPanel
- Run: `php artisan config:clear`

### Missing Assets (CSS/JS)

- Ensure `public/build/` folder exists
- Rebuild assets locally and upload
- Check `public_html` symlink: `ls -la ~/public_html`

## Important Notes

- **Never commit `.env` file** - it contains sensitive credentials
- **npm/Node.js not available** on shared hosting - always build assets locally
- **public_html is a symlink** - don't edit files there, edit in `dentistcms/public/`
- **Always clear caches** after configuration changes
- **Use `--force` flag** for artisan commands in production to skip confirmation prompts

## SSH Access

Default SSH details for Namecheap:
- Host: `server315.web-hosting.com`
- Port: `21098` (check cPanel for your specific port)
- Username: `genecdsh`

## Useful Commands

```bash
# Check PHP version
php -v

# Test Laravel installation
php artisan --version

# List all routes
php artisan route:list

# Clear all caches
php artisan optimize:clear

# View logs
tail -50 storage/logs/laravel.log
```
