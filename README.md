# Dental CMS - Multi-Tenant Practice Management System

A modern, full-featured dental practice management system built with **Laravel 12** and **multi-tenant architecture**. Each dental clinic gets their own isolated environment with a unique subdomain.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.4.1-777BB4?style=flat-square&logo=php)
![TailwindCSS](https://img.shields.io/badge/Tailwind-3.x-38B2AC?style=flat-square&logo=tailwind-css)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=flat-square&logo=alpine.js)

---

## 🎯 Project Overview

This is a comprehensive **SaaS dental practice management system** with:
- **Multi-tenancy**: Each clinic gets a subdomain (e.g., `clinic1.yourdomain.com`)
- **Patient Management**: Complete patient profiles with medical history
- **Appointment Scheduling**: Real-time availability with conflict detection
- **Dentist Management**: Staff profiles and schedules
- **Treatment Catalog**: Service management with pricing
- **Invoice & Billing**: Payment tracking and reporting
- **Super Admin Panel**: Subscription management and system monitoring
- **Demo Account**: Live demo with auto-reset every hour

---

## 🏗️ Architecture

### Multi-Tenant Structure
```
yourdomain.com/admin          → Super Admin Panel
demo.yourdomain.com           → Demo Account (resets hourly)
clinic1.yourdomain.com        → Tenant 1
clinic2.yourdomain.com        → Tenant 2
...
```

### Technology Stack
- **Backend**: Laravel 12, PHP 8.4.1
- **Frontend**: Tailwind CSS 3.x, Alpine.js 3.x
- **Database**: MySQL/PostgreSQL (production), SQLite (testing)
- **Authentication**: Laravel Breeze with 2FA
- **Deployment**: Namecheap hosting, GoDaddy domain

---

## 🚀 Quick Start

### For Local Development

```bash
# Clone repository
git clone https://github.com/sam961/dentistcms.git
cd dentistcms

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Build assets
npm run dev

# Start server
php artisan serve
```

Visit: `http://localhost:8000`

### For Production Deployment (Cloud)

After uploading to your server via SSH:

```bash
# 1. Run migrations
php artisan migrate --force

# 2. Create super admin
php artisan setup:superadmin

# 3. (Optional) Setup demo account
php artisan setup:demo

# 4. Add cron job (for scheduled tasks)
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

## ⚙️ Important Configuration

### Environment Variables (.env)

```env
# Application
APP_NAME="Dental CMS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_DOMAIN=yourdomain.com              # Your base domain (NO http/https)

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Session (CRITICAL for multi-tenant subdomains)
SESSION_DRIVER=database
SESSION_DOMAIN=.yourdomain.com         # Note the leading dot!
SESSION_SECURE_COOKIE=true

# Mail (for 2FA and notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

### DNS Configuration (GoDaddy)

For multi-tenant subdomains to work, add these **A records** at GoDaddy:

```
Type   Host     Value (Namecheap Server IP)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
A      @        123.45.67.89
A      www      123.45.67.89
A      demo     123.45.67.89
A      *        123.45.67.89  ← WILDCARD (enables all subdomains)
```

The **wildcard (`*`)** is essential for tenant subdomains to work.

---

## 🎭 Demo Account

The system includes a fully-functional demo account:

- **URL**: `demo.yourdomain.com`
- **Email**: `demo@dentistcms.com`
- **Password**: `demo123456`
- **2FA Code**: `123456` (static, reusable)

**Features:**
- Pre-loaded with 30 patients, 3 dentists, 60 appointments
- Auto-resets every hour to maintain clean demo environment
- Fully functional - test all features

**Setup Demo:**
```bash
php artisan setup:demo
```

---

## 👤 Super Admin

Create super admin account for managing the entire system:

```bash
php artisan setup:superadmin
```

**Super Admin Panel:**
- URL: `https://yourdomain.com/admin`
- Manage all tenants (dental clinics)
- View subscription analytics
- Monitor system errors
- Track marketing employees

---

## 📋 Core Features

### Patient Management
✅ Complete patient profiles with medical history  
✅ Nationality support (190+ countries)  
✅ Emergency contacts  
✅ Allergies and current medications tracking  
✅ Visit history  

### Appointment System
✅ Multi-step booking (Basic Info → Time Selection → Review)  
✅ Real-time availability checking  
✅ Duration-based conflict detection  
✅ 30-minute time slots (9 AM - 5 PM)  
✅ Weekend blocking  
✅ Status management (scheduled, confirmed, in_progress, completed, cancelled, no_show)  

### Dentist Management
✅ Staff profiles with specializations  
✅ Working hours and availability  
✅ Performance metrics  

### Treatment Catalog
✅ Service management with descriptions  
✅ Pricing configuration  
✅ Duration tracking  
✅ Categories  

### Invoicing & Billing
✅ Automatic invoice generation  
✅ Payment tracking  
✅ Multiple payment methods  
✅ Financial reports  

### Notifications
✅ Real-time in-app notifications  
✅ Unread counter in header  
✅ Mark as read functionality  

### Modern UI/UX
✅ Fully responsive design  
✅ Tailwind CSS styling  
✅ Alpine.js interactivity  
✅ Font Awesome icons  
✅ 3-line headers with descriptions  

---

## 🔒 Security Features

- **2FA Authentication**: Email-based two-factor authentication
- **Password Protection**: Bcrypt hashing
- **CSRF Protection**: Laravel built-in protection
- **SQL Injection Prevention**: Eloquent ORM
- **XSS Protection**: Blade templating engine
- **Email Verification**: Required for new users
- **Session Security**: Secure cookies with domain isolation

---

## 📱 API Endpoints

### Appointment Availability
```bash
GET /api/appointments/available-slots
Parameters:
  - dentist_id: integer
  - date: YYYY-MM-DD
  - duration: integer (minutes)
  - exclude_appointment: integer (optional)

Response:
{
  "available_slots": ["09:00", "09:30", "10:00", ...]
}
```

---

## 🗄️ Database Structure

**Key Tables:**
- `tenants` - Dental clinic accounts (multi-tenant)
- `users` - System users (super admin, clinic admin, dentist, receptionist)
- `patients` - Patient records
- `dentists` - Dentist profiles
- `appointments` - Appointment bookings
- `treatments` - Service catalog
- `invoices` - Billing records
- `notifications` - User notifications
- `verification_codes` - 2FA codes
- `marketing_employees` - Marketing team tracking

---

## 🔧 Deployment Notes

### File Structure on Server (Namecheap)

```
public_html/
├── .env                    ← Production settings
├── .htaccess               ← From public/ folder
├── index.php               ← From public/ folder
├── app/
├── bootstrap/
├── config/
├── database/
├── public/                 ← Keep for assets
├── resources/
├── routes/
├── storage/
├── vendor/
└── artisan
```

**Important**: Move `public/.htaccess` and `public/index.php` to root `public_html/` folder.

### Staging Environment

For testing before production:

```bash
# Copy production to staging folder
cp -r ~/public_html ~/staging

# Update staging .env
APP_ENV=staging
APP_DEBUG=true
APP_URL=https://staging.yourdomain.com

# Use separate database
DB_DATABASE=staging_database

# Run migrations
php artisan migrate --force
```

### Cron Jobs (Required)

Add to cPanel cron jobs:

```bash
* * * * * cd /home/USERNAME/public_html && php artisan schedule:run >> /dev/null 2>&1
```

**Scheduled Tasks:**
- Update past appointment status (every 15 minutes)
- Reset demo data (every hour, only if demo exists)

---

## 🐛 Troubleshooting

### Subdomains Not Working

**Check these 3 critical settings:**

1. **DNS Wildcard at GoDaddy**
   - Must have `*` A record pointing to Namecheap IP

2. **Session Domain in .env**
   ```env
   SESSION_DOMAIN=.yourdomain.com  # Leading dot is CRITICAL!
   SESSION_DRIVER=database
   ```

3. **Clear Cache**
   ```bash
   php artisan config:cache
   ```

### Demo 2FA Code Not Working

The demo 2FA code is: `123456` (static, never expires)

If still not working:
```bash
php artisan setup:demo --fresh
```

### 500 Error

Check permissions:
```bash
chmod -R 755 storage bootstrap/cache
```

Check logs:
```bash
tail -50 storage/logs/laravel.log
```

---

## 📚 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test tests/Feature/AppointmentTest.php

# Run with coverage
php artisan test --coverage
```

---

## 🎨 Code Standards

This project uses **Laravel Pint** for code formatting:

```bash
# Format all files
vendor/bin/pint

# Format only changed files
vendor/bin/pint --dirty

# Check without fixing
vendor/bin/pint --test
```

**Standards:**
- PSR-12 coding standard
- Laravel best practices
- Type declarations required
- PHPDoc blocks for complex logic

---

## 📦 Composer Commands

```bash
# Install production dependencies
composer install --optimize-autoloader --no-dev

# Update dependencies
composer update

# Run code formatter
composer run format

# Run tests
composer run test
```

---

## 🔗 Important Links

- **Repository**: https://github.com/sam961/dentistcms
- **Production**: https://general-station.com
- **Demo**: https://demo.general-station.com
- **Super Admin**: https://general-station.com/admin

---

## 📝 Development Workflow

1. **Local Development** → Test on localhost
2. **Staging** → Deploy to `staging.yourdomain.com`
3. **Production** → Deploy to `yourdomain.com`

Always test on staging before pushing to production!

---

## 🆘 Support

For issues or questions:
- Check `storage/logs/laravel.log` for errors
- Review this README
- Open an issue on GitHub

---

## 📄 License

This project is licensed under the MIT License.

---

## 🙏 Credits

Built with:
- [Laravel Framework](https://laravel.com)
- [Tailwind CSS](https://tailwindcss.com)
- [Alpine.js](https://alpinejs.dev)
- [Font Awesome](https://fontawesome.com)

**Developed with ❤️ for dental practices worldwide**
