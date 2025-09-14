<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Dentist;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentAvailabilityController extends Controller
{
    public function getAvailableSlots(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'dentist_id' => 'required|exists:dentists,id',
            'date' => 'required|date',
            'duration' => 'integer|min:15|max:180',
            'exclude_appointment' => 'integer|exists:appointments,id',
        ]);

        $dentistId = $request->dentist_id;
        $date = Carbon::parse($request->date);
        $requiredDuration = (int) ($request->duration ?? 30);
        $excludeAppointmentId = $request->exclude_appointment;

        // Check if the date is a weekend (assuming dentists don't work weekends)
        if ($date->isWeekend()) {
            return response()->json([
                'available_slots' => [],
                'message' => 'No appointments available on weekends'
            ]);
        }

        // Define working hours (9 AM to 5 PM)
        $workingHours = [
            'start' => 9,
            'end' => 17,
        ];

        // Get existing appointments for this dentist on this date
        $query = Appointment::where('dentist_id', $dentistId)
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', ['cancelled']);

        // Exclude the current appointment when editing
        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        $existingAppointments = $query->get(['appointment_time', 'duration'])
            ->map(function ($appointment) use ($date) {
                $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $appointment->appointment_time);
                $endTime = $startTime->copy()->addMinutes((int) ($appointment->duration ?? 30));

                return [
                    'start' => $startTime->format('H:i'),
                    'end' => $endTime->format('H:i'),
                    'start_carbon' => $startTime,
                    'end_carbon' => $endTime,
                ];
            });

        // Generate all possible time slots (30-minute intervals)
        $allSlots = [];
        $currentTime = $date->copy()->setHour($workingHours['start'])->setMinute(0);
        $endOfDay = $date->copy()->setHour($workingHours['end'])->setMinute(0);

        while ($currentTime->lt($endOfDay)) {
            $slotStart = $currentTime->format('H:i');
            $slotEnd = $currentTime->copy()->addMinutes(30)->format('H:i');

            $allSlots[] = [
                'time' => $slotStart,
                'display' => $currentTime->format('g:i A'),
                'end_time' => $slotEnd,
                'start_carbon' => $currentTime->copy(),
            ];

            $currentTime->addMinutes(30);
        }

        // Filter slots based on required duration and existing appointments
        $availableSlots = collect($allSlots)->filter(function ($slot) use ($existingAppointments, $requiredDuration, $endOfDay) {
            $slotStartTime = $slot['start_carbon'];
            $proposedEndTime = $slotStartTime->copy()->addMinutes($requiredDuration);

            // Check if the proposed appointment would extend beyond working hours
            if ($proposedEndTime->gt($endOfDay)) {
                return false;
            }

            // Check if this time range conflicts with any existing appointment
            foreach ($existingAppointments as $appointment) {
                // Check if the proposed appointment overlaps with existing appointment
                if ($slotStartTime->lt($appointment['end_carbon']) && $proposedEndTime->gt($appointment['start_carbon'])) {
                    return false;
                }
            }

            return true;
        })->values();

        return response()->json([
            'available_slots' => $availableSlots,
            'date' => $date->format('Y-m-d'),
            'dentist_id' => $dentistId,
            'duration' => $requiredDuration,
            'debug' => [
                'total_slots_generated' => count($allSlots),
                'existing_appointments_count' => $existingAppointments->count(),
                'existing_appointments' => $existingAppointments->toArray(),
                'working_hours' => $workingHours,
                'is_weekend' => $date->isWeekend(),
            ]
        ]);
    }

    public function checkSlotAvailability(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'dentist_id' => 'required|exists:dentists,id',
            'date' => 'required|date',
            'time' => 'required',
            'duration' => 'integer|min:15|max:180',
            'exclude_appointment_id' => 'nullable|exists:appointments,id'
        ]);

        $dentistId = $request->dentist_id;
        $date = $request->date;
        $time = $request->time;
        $duration = $request->duration ?? 30;
        $excludeId = $request->exclude_appointment_id;

        $appointmentStart = Carbon::parse($date . ' ' . $time);
        $appointmentEnd = $appointmentStart->copy()->addMinutes($duration);

        // Check for conflicts
        $query = Appointment::where('dentist_id', $dentistId)
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', ['cancelled']);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $conflicts = $query->get()->filter(function ($appointment) use ($appointmentStart, $appointmentEnd) {
            $existingStart = Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);
            $existingEnd = $existingStart->copy()->addMinutes($appointment->duration ?? 30);

            return $appointmentStart->lt($existingEnd) && $appointmentEnd->gt($existingStart);
        });

        return response()->json([
            'available' => $conflicts->isEmpty(),
            'conflicts' => $conflicts->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'patient_name' => $appointment->patient->full_name ?? 'Unknown',
                    'time' => Carbon::parse($appointment->appointment_time)->format('g:i A'),
                    'duration' => $appointment->duration ?? 30,
                ];
            }),
        ]);
    }

    private function timeSlotsOverlap(string $start1, string $end1, string $start2, string $end2): bool
    {
        $start1Time = Carbon::parse($start1);
        $end1Time = Carbon::parse($end1);
        $start2Time = Carbon::parse($start2);
        $end2Time = Carbon::parse($end2);

        return $start1Time->lt($end2Time) && $end1Time->gt($start2Time);
    }

    public function getDentistSchedule(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'dentist_id' => 'required|exists:dentists,id',
            'date' => 'required|date',
        ]);

        $dentistId = $request->dentist_id;
        $date = $request->date;

        $appointments = Appointment::with('patient')
            ->where('dentist_id', $dentistId)
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('appointment_time')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'patient_name' => $appointment->patient->full_name ?? 'Unknown',
                    'start_time' => Carbon::parse($appointment->appointment_time)->format('H:i'),
                    'end_time' => Carbon::parse($appointment->appointment_time)->addMinutes($appointment->duration ?? 30)->format('H:i'),
                    'display_time' => Carbon::parse($appointment->appointment_time)->format('g:i A'),
                    'duration' => $appointment->duration ?? 30,
                    'status' => $appointment->status,
                ];
            });

        return response()->json([
            'appointments' => $appointments,
            'date' => $date,
            'dentist_id' => $dentistId,
        ]);
    }
}