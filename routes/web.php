<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\Api\AppointmentAvailabilityController;
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
});

// API routes for appointment availability - separate group to avoid redirect issues
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/appointments/available-slots', [AppointmentAvailabilityController::class, 'getAvailableSlots'])->name('api.appointments.available-slots');
    Route::post('/appointments/check-availability', [AppointmentAvailabilityController::class, 'checkSlotAvailability'])->name('api.appointments.check-availability');
    Route::get('/dentists/{dentist}/schedule', [AppointmentAvailabilityController::class, 'getDentistSchedule'])->name('api.dentists.schedule');
});

// Debug routes
Route::middleware(['auth'])->group(function () {
    Route::get('/debug/appointments', function() {
        $appointments = \App\Models\Appointment::with(['patient', 'dentist'])
            ->whereDate('appointment_date', today())
            ->get();

        return response()->json([
            'today' => today()->format('Y-m-d'),
            'appointments' => $appointments,
            'total_count' => $appointments->count()
        ]);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
