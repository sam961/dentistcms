<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Dentist;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $medicalRecords = MedicalRecord::with(['patient', 'dentist', 'appointment'])
            ->orderBy('visit_date', 'desc')
            ->paginate(15);
        return view('medical-records.index', compact('medicalRecords'));
    }

    public function create()
    {
        $patients = Patient::orderBy('last_name')->get();
        $dentists = Dentist::where('status', 'active')->orderBy('last_name')->get();
        $appointments = Appointment::with(['patient', 'dentist'])->get();
        return view('medical-records.create', compact('patients', 'dentists', 'appointments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'required|exists:appointments,id',
            'dentist_id' => 'required|exists:dentists,id',
            'visit_date' => 'required|date',
            'chief_complaint' => 'required|string',
            'clinical_findings' => 'required|string',
            'diagnosis' => 'required|string',
            'treatment_provided' => 'required|string',
            'prescription' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'next_visit_date' => 'nullable|date|after:visit_date',
            'tooth_chart' => 'nullable|array',
            'xray_images' => 'nullable|array',
        ]);

        MedicalRecord::create($validated);
        return redirect()->route('medical-records.index')->with('success', 'Medical record created successfully.');
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $medicalRecord->load(['patient', 'dentist', 'appointment']);
        return view('medical-records.show', compact('medicalRecord'));
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        $patients = Patient::orderBy('last_name')->get();
        $dentists = Dentist::where('status', 'active')->orderBy('last_name')->get();
        $appointments = Appointment::with(['patient', 'dentist'])->get();
        return view('medical-records.edit', compact('medicalRecord', 'patients', 'dentists', 'appointments'));
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'required|exists:appointments,id',
            'dentist_id' => 'required|exists:dentists,id',
            'visit_date' => 'required|date',
            'chief_complaint' => 'required|string',
            'clinical_findings' => 'required|string',
            'diagnosis' => 'required|string',
            'treatment_provided' => 'required|string',
            'prescription' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'next_visit_date' => 'nullable|date|after:visit_date',
            'tooth_chart' => 'nullable|array',
            'xray_images' => 'nullable|array',
        ]);

        $medicalRecord->update($validated);
        return redirect()->route('medical-records.index')->with('success', 'Medical record updated successfully.');
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();
        return redirect()->route('medical-records.index')->with('success', 'Medical record deleted successfully.');
    }

    public function patientRecords(Patient $patient)
    {
        $medicalRecords = $patient->medicalRecords()
            ->with(['dentist', 'appointment'])
            ->orderBy('visit_date', 'desc')
            ->paginate(10);
        return view('medical-records.patient', compact('patient', 'medicalRecords'));
    }
}