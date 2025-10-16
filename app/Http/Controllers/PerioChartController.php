<?php

namespace App\Http\Controllers;

use App\Models\Dentist;
use App\Models\Patient;
use App\Models\PerioChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerioChartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PerioChart::with(['patient', 'dentist'])
            ->orderBy('chart_date', 'desc');

        // Filter by patient
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        // Filter by dentist
        if ($request->filled('dentist_id')) {
            $query->where('dentist_id', $request->dentist_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('chart_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('chart_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $perioCharts = $query->paginate(20);
        $patients = Patient::orderBy('last_name')->get();
        $dentists = Dentist::orderBy('name')->get();

        return view('perio-charts.index', compact('perioCharts', 'patients', 'dentists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $patients = Patient::orderBy('last_name')->get();
        $dentists = Dentist::orderBy('name')->get();
        $selectedPatientId = $request->query('patient_id');

        return view('perio-charts.create', compact('patients', 'dentists', 'selectedPatientId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:dentists,id',
            'chart_date' => 'required|date',
            'chart_type' => 'required|in:adult,primary',
            'notes' => 'nullable|string',
        ]);

        $validated['tenant_id'] = Auth::user()->tenant_id ?? null;

        DB::transaction(function () use ($validated, &$perioChart) {
            $perioChart = PerioChart::create($validated);
            $perioChart->createMeasurementsForAllTeeth();
        });

        return redirect()
            ->route('perio-charts.edit', $perioChart)
            ->with('success', 'Perio chart created successfully. Now add measurements.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PerioChart $perioChart)
    {
        $perioChart->load(['patient', 'dentist', 'measurements']);

        return view('perio-charts.show', compact('perioChart'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PerioChart $perioChart)
    {
        $perioChart->load(['patient', 'dentist', 'measurements']);
        $patients = Patient::orderBy('last_name')->get();
        $dentists = Dentist::orderBy('name')->get();

        return view('perio-charts.edit', compact('perioChart', 'patients', 'dentists'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PerioChart $perioChart)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:dentists,id',
            'chart_date' => 'required|date',
            'notes' => 'nullable|string',
            'measurements' => 'array',
        ]);

        DB::transaction(function () use ($validated, $perioChart) {
            $perioChart->update([
                'patient_id' => $validated['patient_id'],
                'dentist_id' => $validated['dentist_id'],
                'chart_date' => $validated['chart_date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update measurements
            if (isset($validated['measurements'])) {
                foreach ($validated['measurements'] as $measurementId => $data) {
                    $perioChart->measurements()
                        ->where('id', $measurementId)
                        ->update($data);
                }
            }
        });

        return redirect()
            ->route('perio-charts.show', $perioChart)
            ->with('success', 'Perio chart updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PerioChart $perioChart)
    {
        $perioChart->delete();

        return redirect()
            ->route('perio-charts.index')
            ->with('success', 'Perio chart deleted successfully.');
    }

    /**
     * Compare two perio charts for the same patient.
     */
    public function compare(Request $request)
    {
        $validated = $request->validate([
            'chart1_id' => 'required|exists:perio_charts,id',
            'chart2_id' => 'required|exists:perio_charts,id',
        ]);

        $chart1 = PerioChart::with(['patient', 'dentist', 'measurements'])
            ->findOrFail($validated['chart1_id']);
        $chart2 = PerioChart::with(['patient', 'dentist', 'measurements'])
            ->findOrFail($validated['chart2_id']);

        // Ensure both charts are for the same patient
        if ($chart1->patient_id !== $chart2->patient_id) {
            return redirect()
                ->back()
                ->with('error', 'Charts must be for the same patient.');
        }

        return view('perio-charts.compare', compact('chart1', 'chart2'));
    }

    /**
     * Print perio chart.
     */
    public function print(PerioChart $perioChart)
    {
        $perioChart->load(['patient', 'dentist', 'measurements']);

        return view('perio-charts.print', compact('perioChart'));
    }
}
