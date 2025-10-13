<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Treatment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'dentist']);

        // Filter by status if specified
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date if specified
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        // Filter by search term if specified
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('patient', function ($q) use ($searchTerm) {
                    $q->where('first_name', 'like', "%{$searchTerm}%")
                        ->orWhere('last_name', 'like', "%{$searchTerm}%")
                        ->orWhere('phone', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%");
                })
                    ->orWhereHas('dentist', function ($q) use ($searchTerm) {
                        $q->where('first_name', 'like', "%{$searchTerm}%")
                            ->orWhere('last_name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhere('type', 'like', "%{$searchTerm}%")
                    ->orWhere('reason', 'like', "%{$searchTerm}%")
                    ->orWhere('notes', 'like', "%{$searchTerm}%");
            });
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15)
            ->appends($request->query());

        return view('appointments.index', compact('appointments'));
    }

    public function create(Request $request)
    {
        $patients = Patient::orderBy('last_name')->get();
        $dentists = Dentist::where('status', 'active')->orderBy('last_name')->get();
        $treatments = Treatment::where('is_active', true)->orderBy('name')->get();

        // Pre-fill data from URL parameters (when coming from calendar)
        $preselectedData = [
            'patient_id' => $request->get('patient_id'),
            'dentist_id' => $request->get('dentist_id'),
            'appointment_date' => $request->get('date'),
            'appointment_time' => $request->get('time'),
            'duration' => $request->get('duration', '30'),
        ];

        return view('appointments.create', compact('patients', 'dentists', 'treatments', 'preselectedData'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:dentists,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:15|max:180|multiple_of:15',
            'type' => 'required|string|max:255',
            'reason' => 'required|string',
            'status' => 'required|in:confirmed,in_progress,completed,cancelled,no_show',
            'notes' => 'nullable|string',
            'treatments' => 'nullable|array',
            'treatments.*' => 'exists:treatments,id',
        ]);

        $appointment = Appointment::create($validated);

        if (! empty($validated['treatments'])) {
            foreach ($validated['treatments'] as $treatmentId) {
                $treatment = Treatment::find($treatmentId);
                if ($treatment) {
                    $appointment->treatments()->attach($treatmentId, [
                        'price' => $treatment->price,
                        'quantity' => 1,
                    ]);
                }
            }
        }

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment created and confirmed successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'dentist', 'treatments', 'medicalRecord', 'invoice']);

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::orderBy('last_name')->get();
        $dentists = Dentist::where('status', 'active')->orderBy('last_name')->get();
        $treatments = Treatment::where('is_active', true)->orderBy('name')->get();

        return view('appointments.edit', compact('appointment', 'patients', 'dentists', 'treatments'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:dentists,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:15|max:180|multiple_of:15',
            'type' => 'required|string|max:255',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:confirmed,in_progress,completed,cancelled,no_show',
            'treatments' => 'nullable|array',
            'treatments.*' => 'exists:treatments,id',
        ]);

        $appointment->update($validated);

        if (isset($validated['treatments'])) {
            $appointment->treatments()->detach();
            foreach ($validated['treatments'] as $treatmentId) {
                $treatment = Treatment::find($treatmentId);
                $appointment->treatments()->attach($treatmentId, [
                    'price' => $treatment->price,
                    'quantity' => 1,
                ]);
            }
        }

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:confirmed,in_progress,completed,cancelled,no_show',
        ]);

        $appointment->update($validated);

        return redirect()->back()
            ->with('success', 'Appointment status updated successfully.');
    }

    public function patientAppointments(Patient $patient)
    {
        $appointments = $patient->appointments()
            ->with('dentist')
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(10);

        return view('appointments.patient', compact('patient', 'appointments'));
    }
}
