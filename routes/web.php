<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\TenantSubscriptionController;
use App\Http\Controllers\Api\AppointmentAvailabilityController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DentalChartController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TreatmentController;
use Illuminate\Support\Facades\Route;

// ===================================================================
// PUBLIC ROUTES - Landing Pages
// ===================================================================
Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/features', [LandingController::class, 'features'])->name('landing.features');
Route::get('/pricing', [LandingController::class, 'pricing'])->name('landing.pricing');
Route::get('/contact', [LandingController::class, 'contact'])->name('landing.contact');

// ===================================================================
// AUTHENTICATION ROUTES (shared by all users)
// ===================================================================
require __DIR__.'/auth.php';

// ===================================================================
// SUPER ADMIN ROUTES
// ===================================================================
Route::middleware(['auth', 'verified', 'super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('tenants', TenantController::class);

    // Subscription analytics
    Route::get('/subscriptions/analytics', [\App\Http\Controllers\Admin\SubscriptionAnalyticsController::class, 'index'])->name('subscriptions.analytics');

    // Tenant subscription management
    Route::get('/tenants/{tenant}/subscription', [TenantSubscriptionController::class, 'index'])->name('tenants.subscription');
    Route::post('/tenants/{tenant}/subscription', [TenantSubscriptionController::class, 'store'])->name('tenants.subscription.store');
    Route::put('/tenants/{tenant}/subscription', [TenantSubscriptionController::class, 'update'])->name('tenants.subscription.update');
    Route::delete('/tenants/{tenant}/subscription', [TenantSubscriptionController::class, 'destroy'])->name('tenants.subscription.destroy');
    Route::post('/tenants/{tenant}/subscription/expire', [TenantSubscriptionController::class, 'expire'])->name('tenants.subscription.expire');

    // Error logs management
    Route::get('/error-logs', [\App\Http\Controllers\Admin\ErrorLogController::class, 'index'])->name('error-logs.index');
    Route::get('/error-logs/{errorLog}', [\App\Http\Controllers\Admin\ErrorLogController::class, 'show'])->name('error-logs.show');
    Route::post('/error-logs/{errorLog}/status', [\App\Http\Controllers\Admin\ErrorLogController::class, 'updateStatus'])->name('error-logs.update-status');
    Route::delete('/error-logs/{errorLog}', [\App\Http\Controllers\Admin\ErrorLogController::class, 'destroy'])->name('error-logs.destroy');
    Route::post('/error-logs/clear-resolved', [\App\Http\Controllers\Admin\ErrorLogController::class, 'clearResolved'])->name('error-logs.clear-resolved');
});

// ===================================================================
// TENANT USER ROUTES (CMS)
// ===================================================================

// Subscription expired page - accessible without subscription check
Route::middleware(['auth', 'verified', 'tenant_user'])->get('/subscription-expired', function () {
    return view('subscription-expired');
})->name('subscription.expired');

// Main tenant CMS routes - require authentication and active subscription
Route::middleware(['auth', 'verified', 'tenant_user', 'check_subscription'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resources
    Route::resource('patients', PatientController::class);
    Route::resource('dentists', DentistController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('treatments', TreatmentController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('medical-records', MedicalRecordController::class);

    // Custom routes
    Route::get('/appointments/{appointment}/invoice', [InvoiceController::class, 'createForAppointment'])->name('appointments.create-invoice');
    Route::get('/patients/{patient}/appointments', [AppointmentController::class, 'patientAppointments'])->name('patients.appointments');
    Route::get('/patients/{patient}/medical-records', [MedicalRecordController::class, 'patientRecords'])->name('patients.medical-records');
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');

    // Dental Chart routes
    Route::get('/patients/{patient}/dental-chart', [DentalChartController::class, 'show'])->name('patients.dental-chart');
    Route::get('/patients/{patient}/dental-chart/tooth/{toothNumber}', [DentalChartController::class, 'getToothHistory'])->name('patients.dental-chart.tooth');
    Route::post('/patients/{patient}/dental-chart/tooth/{toothNumber}', [DentalChartController::class, 'updateTooth'])->name('patients.dental-chart.update');

    // Calendar routes
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

    // Reports routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Notification routes
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Subscription routes - View subscription history only
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===================================================================
// API ROUTES (for authenticated users)
// ===================================================================
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/appointments/available-slots', [AppointmentAvailabilityController::class, 'getAvailableSlots'])->name('api.appointments.available-slots');
    Route::get('/appointments/timeline-slots', [AppointmentAvailabilityController::class, 'getTimelineSlots'])->name('api.appointments.timeline-slots');
    Route::post('/appointments/check-availability', [AppointmentAvailabilityController::class, 'checkSlotAvailability'])->name('api.appointments.check-availability');
    Route::get('/dentists/{dentist}/schedule', [AppointmentAvailabilityController::class, 'getDentistSchedule'])->name('api.dentists.schedule');

    // Calendar API routes
    Route::get('/calendar/appointments', [CalendarController::class, 'getAppointments'])->name('api.calendar.appointments');
    Route::get('/calendar/timeline', [CalendarController::class, 'getDayTimeline'])->name('api.calendar.timeline');
});
