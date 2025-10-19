# Demo Account Credentials

This document contains the credentials for the demo account used to showcase the Dental CMS to potential customers.

## Access Information

- **URL**: demo.dentistcms.test (development) / demo.yourdomain.com (production)
- **Email**: demo@dentistcms.com
- **Password**: demo123456
- **2FA Code**: 123456

## Features

- ✅ Fully functional dental clinic management system
- ✅ Pre-loaded with realistic sample data (30 patients, 3 dentists, 60 appointments)
- ✅ All features unlocked and available for testing
- ✅ Data resets automatically every hour to maintain a clean demo environment
- ✅ Static 2FA code (123456) for easy access

## Demo Data

The demo account includes:

- **5 Treatments**: Dental Cleaning, Filling, Root Canal, Extraction, Crown
- **3 Dentists**: One for each specialization (General, Orthodontics, Endodontics)
- **30 Patients**: Realistic patient profiles with contact information
- **60 Appointments**: Mix of past and future appointments

## Automated Reset

The demo data resets every hour via Laravel's scheduler:

```bash
# Manual reset (if needed)
php artisan demo:reset --force

# The scheduler runs automatically via cron:
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## Initial Setup

To set up the demo account for the first time:

```bash
# Create demo tenant and admin user
php artisan db:seed --class=DemoAdminSeeder

# Seed demo data
php artisan db:seed --class=TenantDataSeeder
```

## Notes for Customers

- The demo account is read-write - feel free to create, edit, and delete data
- All changes will be reset within the next hour
- No credit card required for demo access
- Perfect for evaluating all system features before purchasing
