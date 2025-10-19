<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Dentist;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(Request $request): View
    {
        $dentists = Dentist::orderBy('first_name')->get();
        $selectedDate = $request->get('date', now()->format('Y-m-d'));
        $selectedDentists = $request->get('dentists', []);

        return view('calendar.index', compact('dentists', 'selectedDate', 'selectedDentists'));
    }

    public function getAppointments(Request $request): JsonResponse
    {
        $date = Carbon::parse($request->get('date', now()));
        $view = $request->get('view', 'day'); // day, week, month
        $dentistIds = $request->get('dentists', []);

        // Convert comma-separated string to array if needed
        if (is_string($dentistIds) && ! empty($dentistIds)) {
            $dentistIds = explode(',', $dentistIds);
            $dentistIds = array_map('trim', $dentistIds);
            $dentistIds = array_filter($dentistIds);
        }

        $query = Appointment::with(['patient', 'dentist', 'treatments']);

        // Filter by dentists if specified
        if (! empty($dentistIds)) {
            $query->whereIn('dentist_id', $dentistIds);
        }

        // Date range based on view type
        switch ($view) {
            case 'month':
                $startDate = $date->copy()->startOfMonth();
                $endDate = $date->copy()->endOfMonth();
                break;
            case 'week':
                $startDate = $date->copy()->startOfWeek();
                $endDate = $date->copy()->endOfWeek();
                break;
            default: // day
                $startDate = $date->copy()->startOfDay();
                $endDate = $date->copy()->endOfDay();
        }

        $appointments = $query->whereBetween('appointment_date', [$startDate, $endDate])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        // Format appointments for calendar display
        $formattedAppointments = $appointments->map(function ($appointment) {
            $startTime = Carbon::parse($appointment->appointment_date->format('Y-m-d').' '.$appointment->appointment_time);
            $endTime = $startTime->copy()->addMinutes($appointment->duration);

            return [
                'id' => $appointment->id,
                'title' => $appointment->patient->full_name,
                'patient' => $appointment->patient->full_name,
                'dentist' => $appointment->dentist->full_name,
                'dentist_id' => $appointment->dentist_id,
                'treatment' => $appointment->treatments->first()?->name ?? $appointment->type,
                'start' => $startTime->format('Y-m-d H:i:s'),
                'end' => $endTime->format('Y-m-d H:i:s'),
                'date' => $appointment->appointment_date->format('Y-m-d'),
                'time' => $appointment->appointment_time,
                'duration' => $appointment->duration,
                'status' => $appointment->status,
                'color' => $this->getDentistColor($appointment->dentist_id),
                'notes' => $appointment->notes,
            ];
        });

        return response()->json([
            'appointments' => $formattedAppointments,
            'view' => $view,
            'date' => $date->format('Y-m-d'),
        ]);
    }

    public function getDayTimeline(Request $request): JsonResponse
    {
        $date = Carbon::parse($request->get('date', now()));
        $dentistIds = $request->get('dentists', []);

        // Convert comma-separated string to array if needed
        if (is_string($dentistIds) && ! empty($dentistIds)) {
            $dentistIds = explode(',', $dentistIds);
            $dentistIds = array_map('trim', $dentistIds);
            $dentistIds = array_filter($dentistIds);
        }

        // Get all dentists or filtered ones
        $dentistsQuery = Dentist::query();
        if (! empty($dentistIds)) {
            $dentistsQuery->whereIn('id', $dentistIds);
        }
        $dentists = $dentistsQuery->orderBy('first_name')->get();

        // Get only confirmed appointments for the day
        $appointments = Appointment::with(['patient', 'treatments'])
            ->whereDate('appointment_date', $date)
            ->where('status', 'confirmed')
            ->when(! empty($dentistIds), function ($query) use ($dentistIds) {
                $query->whereIn('dentist_id', $dentistIds);
            })
            ->get();

        // Build timeline data for each dentist
        $timeline = [];
        foreach ($dentists as $dentist) {
            $dentistAppointments = $appointments->where('dentist_id', $dentist->id);

            $timeSlots = [];
            $workStart = Carbon::parse($date->format('Y-m-d').' 09:00:00');
            $workEnd = Carbon::parse($date->format('Y-m-d').' 17:00:00');

            // Generate 15-minute time slots
            $currentTime = $workStart->copy();
            while ($currentTime < $workEnd) {
                $slotEnd = $currentTime->copy()->addMinutes(15);

                // Check if this slot has an appointment
                $appointment = null;
                $isOccupied = false;

                foreach ($dentistAppointments as $apt) {
                    $aptStart = Carbon::parse($date->format('Y-m-d').' '.$apt->appointment_time);
                    $aptEnd = $aptStart->copy()->addMinutes($apt->duration);

                    // Check if this slot overlaps with an appointment
                    if ($currentTime < $aptEnd && $slotEnd > $aptStart) {
                        $isOccupied = true;
                        if ($currentTime == $aptStart) {
                            $appointment = [
                                'id' => $apt->id,
                                'patient' => $apt->patient->full_name,
                                'treatment' => $apt->treatments->first()?->name ?? $apt->type,
                                'duration' => $apt->duration,
                                'status' => $apt->status,
                                'time' => $aptStart->format('g:i A'),
                                'spans' => ceil($apt->duration / 15), // How many 15-min slots it spans
                            ];
                        }
                        break;
                    }
                }

                $timeSlots[] = [
                    'time' => $currentTime->format('H:i'),
                    'isOccupied' => $isOccupied,
                    'appointment' => $appointment,
                ];

                $currentTime->addMinutes(15);
            }

            $timeline[] = [
                'dentist' => [
                    'id' => $dentist->id,
                    'name' => $dentist->full_name,
                    'specialization' => $dentist->specialization,
                    'color' => $this->getDentistColor($dentist->id),
                ],
                'timeSlots' => $timeSlots,
                'totalAppointments' => $dentistAppointments->count(),
                'occupancyRate' => $this->calculateOccupancy($timeSlots),
            ];
        }

        return response()->json([
            'date' => $date->format('Y-m-d'),
            'timeline' => $timeline,
            'timeRange' => [
                'start' => '09:00',
                'end' => '17:00',
            ],
        ]);
    }

    private function getDentistColor(int $dentistId): string
    {
        $colors = [
            '#3B82F6', // blue
            '#10B981', // green
            '#F59E0B', // amber
            '#EF4444', // red
            '#8B5CF6', // violet
            '#EC4899', // pink
            '#14B8A6', // teal
            '#F97316', // orange
        ];

        return $colors[($dentistId - 1) % count($colors)];
    }

    private function calculateOccupancy(array $timeSlots): float
    {
        $occupied = collect($timeSlots)->where('isOccupied', true)->count();
        $total = count($timeSlots);

        return $total > 0 ? round(($occupied / $total) * 100, 1) : 0;
    }
}
