<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\TenantSubscriptionController;
use App\Http\Controllers\Api\AppointmentAvailabilityController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DentalChartController;
use App\Http\Controllers\DentalImageController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\TreatmentPlanController;
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

// Email Verification Routes
Route::get('/verify-email/{token}', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'verify'])
    ->name('verification.verify');
Route::post('/verify-email/resend', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'resend'])
    ->name('verification.resend');

// 2FA Login Routes
Route::get('/login/verify', [\App\Http\Controllers\Auth\TwoFactorController::class, 'show'])
    ->name('login.2fa.show');
Route::post('/login/verify', [\App\Http\Controllers\Auth\TwoFactorController::class, 'verify'])
    ->name('login.2fa.verify');
Route::post('/login/verify/resend', [\App\Http\Controllers\Auth\TwoFactorController::class, 'resend'])
    ->name('login.2fa.resend');

// ===================================================================
// SUPER ADMIN ROUTES
// ===================================================================
Route::middleware(['auth', 'verified', 'super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('tenants', TenantController::class);

    // Revenue & Financial Analytics
    Route::get('/revenue-analytics', [\App\Http\Controllers\Admin\RevenueAnalyticsController::class, 'index'])->name('revenue.analytics');

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
    Route::resource('treatment-plans', TreatmentPlanController::class);
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

    // Dental Image routes
    Route::prefix('patients/{patient}/images')->group(function () {
        Route::get('/', [DentalImageController::class, 'index'])->name('patients.images.index');
        Route::get('/create', [DentalImageController::class, 'create'])->name('patients.images.create');
        Route::post('/', [DentalImageController::class, 'store'])->name('patients.images.store');
        Route::get('/{image}', [DentalImageController::class, 'show'])->name('patients.images.show');
        Route::get('/{image}/download', [DentalImageController::class, 'download'])->name('patients.images.download');
        Route::get('/{image}/thumbnail', [DentalImageController::class, 'thumbnail'])->name('patients.images.thumbnail');
        Route::delete('/{image}', [DentalImageController::class, 'destroy'])->name('patients.images.destroy');
    });

    // Treatment Plan additional routes
    Route::post('/treatment-plans/{treatmentPlan}/status/{status}', [TreatmentPlanController::class, 'updateStatus'])->name('treatment-plans.update-status');
    Route::post('/treatment-plans/{treatmentPlan}/items', [TreatmentPlanController::class, 'addItem'])->name('treatment-plans.add-item');
    Route::delete('/treatment-plans/{treatmentPlan}/items/{item}', [TreatmentPlanController::class, 'removeItem'])->name('treatment-plans.remove-item');
    Route::post('/treatment-plans/{treatmentPlan}/items/{item}/status/{status}', [TreatmentPlanController::class, 'updateItemStatus'])->name('treatment-plans.update-item-status');
    Route::post('/treatment-plans/{treatmentPlan}/email', [TreatmentPlanController::class, 'sendEmail'])->name('treatment-plans.email');

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
