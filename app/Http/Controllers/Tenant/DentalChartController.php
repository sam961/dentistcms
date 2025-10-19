<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\ToothRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DentalChartController extends Controller
{
    public function show(Patient $patient): View
    {
        $toothRecords = $patient->toothRecords()
            ->with(['treatment', 'dentist', 'medicalRecord'])
            ->orderBy('treatment_date', 'desc')
            ->get()
            ->groupBy('tooth_number');

        return view('dental-chart.show', compact('patient', 'toothRecords'));
    }

    public function getToothHistory(Patient $patient, string $toothNumber): JsonResponse
    {
        $records = $patient->toothRecords()
            ->where('tooth_number', $toothNumber)
            ->with(['treatment', 'dentist', 'medicalRecord'])
            ->orderBy('treatment_date', 'desc')
            ->get();

        $toothName = ToothRecord::getToothName($toothNumber);

        return response()->json([
            'tooth_number' => $toothNumber,
            'tooth_name' => $toothName,
            'records' => $records->map(function ($record) {
                return [
                    'id' => $record->id,
                    'condition' => $record->condition,
                    'notes' => $record->notes,
                    'treatment_date' => $record->treatment_date?->format('Y-m-d'),
                    'treatment' => $record->treatment ? [
                        'name' => $record->treatment->name,
                        'description' => $record->treatment->description,
                    ] : null,
                    'dentist' => $record->dentist ? [
                        'name' => $record->dentist->full_name,
                        'specialization' => $record->dentist->specialization,
                    ] : null,
                    'surface' => $record->surface,
                    'severity' => $record->severity,
                ];
            }),
        ]);
    }

    public function updateTooth(Request $request, Patient $patient, string $toothNumber): JsonResponse
    {
        $validated = $request->validate([
            'condition' => 'required|string|in:healthy,cavity,filled,crown,root_canal,missing,implant',
            'notes' => 'nullable|string',
            'treatment_id' => 'nullable|exists:treatments,id',
            'dentist_id' => 'nullable|exists:dentists,id',
            'medical_record_id' => 'nullable|exists:medical_records,id',
            'surface' => 'nullable|string',
            'severity' => 'nullable|string|in:mild,moderate,severe',
            'treatment_date' => 'nullable|date',
        ]);

        $toothRecord = $patient->toothRecords()->create([
            'tooth_number' => $toothNumber,
            'tooth_type' => is_numeric($toothNumber) ? 'permanent' : 'primary',
            ...$validated,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tooth record updated successfully',
            'record' => $toothRecord,
        ]);
    }
}
