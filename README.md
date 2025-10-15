# Dental CMS - Comprehensive Practice Management System

A modern, full-featured dental practice management system built with Laravel 12, designed to streamline clinic operations, patient management, and appointment scheduling.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.4.1-777BB4?style=flat-square&logo=php)
![TailwindCSS](https://img.shields.io/badge/Tailwind-3.x-38B2AC?style=flat-square&logo=tailwind-css)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=flat-square&logo=alpine.js)

## Table of Contents

- [Features](#features)
- [Technology Stack](#technology-stack)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Key Features Deep Dive](#key-features-deep-dive)
- [Screenshots](#screenshots)
- [Contributing](#contributing)
- [License](#license)

## Features

### Patient Management
- **Comprehensive Patient Profiles**: Complete patient records with personal information, medical history, and contact details
- **Nationality Support**: Searchable Select2 dropdown with 190+ nationalities
- **Flexible Contact Information**: Optional email, address, and city fields for improved data collection
- **Emergency Contacts**: Store emergency contact information for each patient
- **Medical History Tracking**: Detailed medical history, allergies, and current medications
- **Visit History**: Complete treatment and visit history for each patient

### Appointment System
- **Multi-Step Booking Process**: Intuitive 3-step appointment creation and editing
  1. Basic Information (patient, dentist, treatment selection)
  2. Time Selection (real-time availability checking)
  3. Review and Confirmation
- **Real-Time Availability**: Live checking of available time slots based on dentist schedules
- **Conflict Prevention**: Duration-based overlap detection prevents double-bookings
- **Time Slot Management**: 30-minute intervals from 9 AM to 5 PM
- **Weekend Blocking**: Automatically excludes weekends from available slots
- **Status Management**: Tag-based filtering (scheduled, confirmed, in_progress, completed, cancelled, no_show)
- **Calendar Integration**: Flatpickr calendar widget for seamless date selection
- **Smart Editing**: Excludes current appointment when checking for conflicts during edits

### Dentist Management
- **Staff Profiles**: Complete dentist information with specializations
- **Schedule Management**: Define working hours and availability
- **Specialization Tracking**: Track dentist specialties and expertise areas
- **Performance Metrics**: View appointment and treatment statistics per dentist

### Treatment Catalog
- **Service Management**: Comprehensive treatment catalog with descriptions
- **Pricing Configuration**: Set and manage treatment prices
- **Duration Tracking**: Define expected duration for each treatment type
- **Treatment Categories**: Organize treatments by categories

### Invoice & Billing
- **Invoice Generation**: Automatic invoice creation from appointments
- **Payment Tracking**: Record and track payments
- **Payment Status**: Monitor paid, pending, and overdue invoices
- **Payment Methods**: Support for multiple payment methods (cash, card, transfer)
- **Financial Reports**: Generate revenue and payment reports

### Notifications System
- **Real-Time Notifications**: In-app notification system for important events
- **Unread Counter**: Visual indicator for unread notifications in header
- **Notification Center**: Dedicated page to view all notifications
- **Mark as Read**: Individual and bulk mark-as-read functionality
- **Notification Types**: Appointment reminders, payment notifications, system alerts

### Super Admin Dashboard
- **Subscription Analytics**: Comprehensive subscription metrics and insights
- **Error Log Management**: Track and resolve application errors
- **User Management**: Manage clinic users and permissions
- **System Monitoring**: Monitor application health and performance
- **Activity Logs**: Track user actions and system events

### Modern UI/UX
- **Responsive Design**: Fully responsive across all device sizes
- **Tailwind CSS**: Modern utility-first CSS framework
- **Alpine.js**: Lightweight JavaScript framework for interactivity
- **Font Awesome Icons**: Comprehensive icon library
- **Success Notifications**: Consistent notification design with green alerts
- **3-Line Headers**: Modern header design with icons and descriptions
- **Card-Based Layout**: Clean, modern card-based UI components

## Technology Stack

### Backend
- **PHP**: 8.4.1
- **Laravel Framework**: 12.x
- **Laravel Breeze**: 2.x (Authentication scaffolding)
- **Laravel Pint**: 1.x (Code formatting)
- **Laravel Sail**: 1.x (Docker development environment)
- **PHPUnit**: 11.x (Testing framework)

### Frontend
- **Tailwind CSS**: 3.x
- **Alpine.js**: 3.x
- **Flatpickr**: Date/time picker
- **Select2**: Enhanced select dropdowns
- **Font Awesome**: Icon library
- **Chart.js**: Data visualization (for analytics)

### Database
- **MySQL**: 8.x (Production)
- **SQLite**: (Testing)

### Development Tools
- **Vite**: Fast frontend build tool
- **Laravel Debugbar**: Development debugging
- **Composer**: PHP dependency management
- **NPM**: JavaScript package management

## Installation

### Prerequisites
- PHP >= 8.4.1
- Composer
- Node.js & NPM
- MySQL or MariaDB
- Git

### Step 1: Clone the Repository
```bash
git clone git@github.com:sam961/dentistcms.git
cd dentistcms
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### Step 3: Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup
```bash
# Update .env with your database credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dentist_cms
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# (Optional) Seed the database with sample data
php artisan db:seed
```

### Step 5: Build Frontend Assets
```bash
# Development build
npm run dev

# Production build
npm run build
```

### Step 6: Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Configuration

### Mail Configuration
Configure your mail settings in `.env` for appointment reminders and notifications:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@dentalcms.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Queue Configuration
For handling background jobs (notifications, emails):
```bash
# Run queue worker
php artisan queue:work

# Or use Laravel Horizon for advanced queue management
composer require laravel/horizon
php artisan horizon:install
php artisan horizon
```

### File Storage
Configure file storage for patient documents:
```bash
php artisan storage:link
```

## Usage

### Default User Accounts
After seeding, you can log in with:

**Admin Account:**
- Email: admin@example.com
- Password: password

**Dentist Account:**
- Email: dentist@example.com
- Password: password

### Creating Your First Patient
1. Navigate to **Patients** → **Add New Patient**
2. Fill in patient information (required fields: name, date of birth, gender, phone)
3. Add optional information (email, address, nationality, medical history)
4. Click **Create Patient**

### Booking an Appointment
1. Navigate to **Appointments** → **Schedule Appointment**
2. **Step 1**: Select patient, dentist, and treatment
3. **Step 2**: Choose available date and time slot
4. **Step 3**: Review and confirm appointment details
5. Click **Book Appointment**

### Managing Invoices
1. Navigate to **Invoices**
2. Create invoices from appointments or manually
3. Record payments as they are received
4. Generate invoice reports

## Key Features Deep Dive

### Appointment Availability API
The system includes a sophisticated availability checking API:

**Endpoint:** `/api/appointments/available-slots`

**Parameters:**
- `dentist_id`: ID of the dentist
- `date`: Date to check (YYYY-MM-DD)
- `duration`: Appointment duration in minutes
- `exclude_appointment`: (Optional) Appointment ID to exclude from conflict checking

**Response:**
```json
{
  "available_slots": [
    "09:00", "09:30", "10:00", "10:30",
    "14:00", "14:30", "15:00"
  ]
}
```

### Notification System
Notifications are stored in the database and displayed in real-time:

**Creating a Notification:**
```php
$user->notify(new AppointmentReminder($appointment));
```

**Notification Structure:**
```php
[
    'title' => 'Appointment Reminder',
    'message' => 'You have an appointment tomorrow at 10:00 AM',
    'action_url' => route('appointments.show', $appointment),
    'icon' => 'calendar'
]
```

### Database Structure
Key tables and relationships:
- **patients**: Patient information and medical history
- **dentists**: Dentist profiles and specializations
- **appointments**: Appointment bookings (links patients, dentists, treatments)
- **treatments**: Service catalog with pricing
- **invoices**: Billing and payment records
- **notifications**: User notifications
- **error_logs**: Application error tracking (super admin)

## Testing

Run the test suite:
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AppointmentTest.php

# Run with coverage
php artisan test --coverage
```

## Code Style

This project follows Laravel best practices and uses Laravel Pint for code formatting:
```bash
# Format code
./vendor/bin/pint

# Check code without fixing
./vendor/bin/pint --test
```

## API Documentation

### Available Endpoints
- `GET /api/appointments/available-slots` - Get available appointment slots
- `GET /api/treatments` - List all treatments
- `GET /api/dentists/{id}/schedule` - Get dentist schedule

### Authentication
API endpoints require Bearer token authentication:
```bash
Authorization: Bearer {your-token}
```

## Deployment

### Production Checklist
- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Configure production database credentials
- [ ] Set up queue workers
- [ ] Configure mail server
- [ ] Enable SSL/HTTPS
- [ ] Set up regular database backups
- [ ] Configure cron for scheduled tasks
- [ ] Optimize application (`php artisan optimize`)
- [ ] Build production assets (`npm run build`)

### Scheduled Tasks
Add to crontab:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Security Vulnerabilities

If you discover a security vulnerability, please send an email to security@dentalcms.com. All security vulnerabilities will be promptly addressed.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, please open an issue on GitHub or contact support@dentalcms.com.

## Acknowledgments

- Built with [Laravel](https://laravel.com)
- UI components styled with [Tailwind CSS](https://tailwindcss.com)
- Icons by [Font Awesome](https://fontawesome.com)
- Enhanced by [Alpine.js](https://alpinejs.dev)

---

**Developed with ❤️ for dental practices worldwide**
