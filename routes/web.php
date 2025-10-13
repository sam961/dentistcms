<?php

use App\Http\Controllers\Api\AppointmentAvailabilityController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DentalChartController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TreatmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('patients', PatientController::class);
    Route::resource('dentists', DentistController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('treatments', TreatmentController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('medical-records', MedicalRecordController::class);

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

    // Subscription routes
    Route::resource('subscriptions', SubscriptionController::class);
    Route::post('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::post('/subscriptions/{subscription}/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');
});

// API routes for appointment availability - separate group to avoid redirect issues
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/appointments/available-slots', [AppointmentAvailabilityController::class, 'getAvailableSlots'])->name('api.appointments.available-slots');
    Route::get('/appointments/timeline-slots', [AppointmentAvailabilityController::class, 'getTimelineSlots'])->name('api.appointments.timeline-slots');
    Route::post('/appointments/check-availability', [AppointmentAvailabilityController::class, 'checkSlotAvailability'])->name('api.appointments.check-availability');
    Route::get('/dentists/{dentist}/schedule', [AppointmentAvailabilityController::class, 'getDentistSchedule'])->name('api.dentists.schedule');

    // Calendar API routes
    Route::get('/calendar/appointments', [CalendarController::class, 'getAppointments'])->name('api.calendar.appointments');
    Route::get('/calendar/timeline', [CalendarController::class, 'getDayTimeline'])->name('api.calendar.timeline');
});

// Debug routes
Route::middleware(['auth'])->group(function () {
    Route::get('/debug/appointments', function () {
        $appointments = \App\Models\Appointment::with(['patient', 'dentist'])
            ->whereDate('appointment_date', today())
            ->get();

        return response()->json([
            'today' => today()->format('Y-m-d'),
            'appointments' => $appointments,
            'total_count' => $appointments->count(),
        ]);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
