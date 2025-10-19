<?php

namespace App\Actions\Appointments;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class CreateAppointmentAction
{
    public function execute(array $data): Appointment
    {
        return DB::transaction(function () use ($data) {
            $appointment = Appointment::create([
                'patient_id' => $data['patient_id'],
                'dentist_id' => $data['dentist_id'],
                'treatment_id' => $data['treatment_id'] ?? null,
                'appointment_date' => $data['appointment_date'],
                'appointment_time' => $data['appointment_time'],
                'duration_minutes' => $data['duration_minutes'] ?? 30,
                'status' => $data['status'] ?? 'scheduled',
                'appointment_type' => $data['appointment_type'] ?? 'checkup',
                'reason' => $data['reason'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // Future: Dispatch AppointmentCreated event
            // event(new AppointmentCreated($appointment));

            return $appointment;
        });
    }
}
