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
                'message' => 'No appointments available on weekends',
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
                $startTime = Carbon::parse($date->format('Y-m-d').' '.$appointment->appointment_time);
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
            ],
        ]);
    }

    public function checkSlotAvailability(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'dentist_id' => 'required|exists:dentists,id',
            'date' => 'required|date',
            'time' => 'required',
            'duration' => 'integer|min:15|max:180',
            'exclude_appointment_id' => 'nullable|exists:appointments,id',
        ]);

        $dentistId = $request->dentist_id;
        $date = $request->date;
        $time = $request->time;
        $duration = $request->duration ?? 30;
        $excludeId = $request->exclude_appointment_id;

        $appointmentStart = Carbon::parse($date.' '.$time);
        $appointmentEnd = $appointmentStart->copy()->addMinutes($duration);

        // Check for conflicts
        $query = Appointment::where('dentist_id', $dentistId)
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', ['cancelled']);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $conflicts = $query->get()->filter(function ($appointment) use ($appointmentStart, $appointmentEnd) {
            $existingStart = Carbon::parse($appointment->appointment_date.' '.$appointment->appointment_time);
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

    public function getTimelineSlots(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'dentist_id' => 'required|exists:dentists,id',
            'date' => 'required|date',
            'duration' => 'integer|min:15|max:240',
        ]);

        $dentistId = $request->dentist_id;
        $date = Carbon::parse($request->date);
        $requiredDuration = (int) ($request->duration ?? 30);

        // Get dentist working hours
        $dentist = Dentist::find($dentistId);
        $workingHoursStart = $dentist->working_hours_start ? Carbon::parse($dentist->working_hours_start)->hour : 9;
        $workingHoursEnd = $dentist->working_hours_end ? Carbon::parse($dentist->working_hours_end)->hour : 17;

        // Check if the date is in working days
        $dayOfWeek = strtolower($date->format('l')); // e.g., "monday"
        $workingDays = $dentist->working_days ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        // Normalize working days to lowercase for comparison
        $workingDays = array_map('strtolower', $workingDays);

        if (! in_array($dayOfWeek, $workingDays)) {
            return response()->json([
                'timeline_slots' => [],
                'message' => 'Dentist is not working on this day',
            ]);
        }

        // Get existing appointments
        $existingAppointments = Appointment::with('patient')
            ->where('dentist_id', $dentistId)
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->get()
            ->map(function ($appointment) use ($date) {
                $startTime = Carbon::parse($date->format('Y-m-d').' '.$appointment->appointment_time);
                $endTime = $startTime->copy()->addMinutes((int) ($appointment->duration ?? 30));

                return [
                    'id' => $appointment->id,
                    'patient_name' => $appointment->patient->full_name ?? 'Unknown',
                    'start' => $startTime,
                    'end' => $endTime,
                    'start_formatted' => $startTime->format('H:i'),
                    'end_formatted' => $endTime->format('H:i'),
                    'duration' => $appointment->duration ?? 30,
                ];
            });

        // Generate timeline slots (15-minute intervals)
        $timelineSlots = [];
        $currentTime = $date->copy()->setHour($workingHoursStart)->setMinute(0);
        $endOfDay = $date->copy()->setHour($workingHoursEnd)->setMinute(0);

        // Define break times (12:00 PM - 1:00 PM)
        $breakStart = $date->copy()->setHour(12)->setMinute(0);
        $breakEnd = $date->copy()->setHour(13)->setMinute(0);

        while ($currentTime->lt($endOfDay)) {
            $slotTime = $currentTime->format('H:i');
            $displayTime = $currentTime->format('g:i A');

            // Calculate slot end time (15 minutes later)
            $slotEnd = $currentTime->copy()->addMinutes(15);

            // Determine slot status
            $status = 'available';
            $info = '';
            $tooltip = 'Available - Click to book';

            // Check if it's break time
            if ($currentTime->gte($breakStart) && $currentTime->lt($breakEnd)) {
                $status = 'break';
                $info = 'Lunch Break';
                $tooltip = 'Lunch break time';
            } else {
                // Check if slot overlaps with any existing appointments
                foreach ($existingAppointments as $appointment) {
                    // A slot overlaps if:
                    // Slot starts before appointment ends AND slot ends after appointment starts
                    if ($currentTime->lt($appointment['end']) && $slotEnd->gt($appointment['start'])) {
                        $status = 'booked';
                        $info = $appointment['patient_name'];
                        $tooltip = sprintf(
                            'Booked by %s (%s - %s, %d min)',
                            $appointment['patient_name'],
                            $appointment['start_formatted'],
                            $appointment['end_formatted'],
                            $appointment['duration']
                        );
                        break;
                    }
                }
            }

            $timelineSlots[] = [
                'time' => $slotTime,
                'display' => $displayTime,
                'status' => $status,
                'info' => $info,
                'tooltip' => $tooltip,
            ];

            $currentTime->addMinutes(15); // 15-minute intervals
        }

        // Prepare appointment blocks for visual display
        $appointmentBlocks = $existingAppointments->map(function ($appointment) use ($workingHoursStart) {
            return [
                'id' => $appointment['id'],
                'patient_name' => $appointment['patient_name'],
                'start_time' => $appointment['start_formatted'],
                'end_time' => $appointment['end_formatted'],
                'start_display' => $appointment['start']->format('g:i A'),
                'end_display' => $appointment['end']->format('g:i A'),
                'duration' => $appointment['duration'],
                'start_minutes' => ($appointment['start']->hour - $workingHoursStart) * 60 + $appointment['start']->minute,
            ];
        })->values();

        return response()->json([
            'timeline_slots' => $timelineSlots,
            'appointment_blocks' => $appointmentBlocks,
            'date' => $date->format('Y-m-d'),
            'dentist_id' => $dentistId,
            'duration' => $requiredDuration,
            'working_hours' => [
                'start' => $workingHoursStart,
                'end' => $workingHoursEnd,
            ],
            'debug' => [
                'total_appointments_found' => $existingAppointments->count(),
                'appointments_raw' => $existingAppointments->map(function ($apt) {
                    return [
                        'id' => $apt['id'],
                        'patient' => $apt['patient_name'],
                        'start' => $apt['start']->format('Y-m-d H:i:s'),
                        'end' => $apt['end']->format('Y-m-d H:i:s'),
                        'duration' => $apt['duration'],
                    ];
                })->toArray(),
                'booked_slots_count' => count(array_filter($timelineSlots, fn ($slot) => $slot['status'] === 'booked')),
            ],
        ]);
    }

    public function getDentistSchedule(Request $request, $dentist): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $dentistId = $dentist;
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
