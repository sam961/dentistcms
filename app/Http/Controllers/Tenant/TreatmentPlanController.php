<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTreatmentPlanRequest;
use App\Http\Requests\UpdateTreatmentPlanRequest;
use App\Mail\TreatmentPlanMail;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class TreatmentPlanController extends Controller
{
    public function index(): View
    {
        $query = TreatmentPlan::with(['patient', 'dentist']);

        // Filter by patient if provided
        if (request()->has('patient_id')) {
            $query->where('patient_id', request('patient_id'));
        }

        // Filter by status if provided
        if (request()->has('status') && request('status') !== '') {
            $query->where('status', request('status'));
        }

        // Filter by phase if provided
        if (request()->has('phase') && request('phase') !== '') {
            $query->where('phase', request('phase'));
        }

        $treatmentPlans = $query->latest()->paginate(15);

        return view('treatment-plans.index', compact('treatmentPlans'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get();
        $dentists = Dentist::orderBy('first_name')->get();
        $treatments = Treatment::orderBy('name')->get();

        // Pre-select patient if coming from patient profile
        $selectedPatientId = request('patient_id');

        return view('treatment-plans.create', compact('patients', 'dentists', 'treatments', 'selectedPatientId'));
    }

    public function store(StoreTreatmentPlanRequest $request): RedirectResponse
    {
        $treatmentPlan = TreatmentPlan::create($request->validated());

        // Create treatment plan items if provided
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $itemData) {
                $treatment = Treatment::find($itemData['treatment_id']);

                TreatmentPlanItem::create([
                    'treatment_plan_id' => $treatmentPlan->id,
                    'treatment_id' => $itemData['treatment_id'],
                    'tooth_number' => $itemData['tooth_number'] ?? null,
                    'tooth_surface' => $itemData['tooth_surface'] ?? null,
                    'quantity' => $itemData['quantity'] ?? 1,
                    'unit_cost' => $treatment->price,
                    'total_cost' => $treatment->price * ($itemData['quantity'] ?? 1),
                    'insurance_estimate' => $itemData['insurance_estimate'] ?? 0,
                    'notes' => $itemData['notes'] ?? null,
                ]);
            }

            // Recalculate totals after adding items
            $treatmentPlan->refresh();
            $treatmentPlan->recalculateTotals();
        }

        return redirect()
            ->route('treatment-plans.show', $treatmentPlan)
            ->with('success', 'Treatment plan created successfully.');
    }

    public function show(TreatmentPlan $treatmentPlan): View
    {
        $treatmentPlan->load(['patient', 'dentist', 'items.treatment']);

        return view('treatment-plans.show', compact('treatmentPlan'));
    }

    public function edit(TreatmentPlan $treatmentPlan): View
    {
        $treatmentPlan->load('items.treatment');
        $patients = Patient::orderBy('first_name')->get();
        $dentists = Dentist::orderBy('first_name')->get();
        $treatments = Treatment::orderBy('name')->get();

        return view('treatment-plans.edit', compact('treatmentPlan', 'patients', 'dentists', 'treatments'));
    }

    public function update(UpdateTreatmentPlanRequest $request, TreatmentPlan $treatmentPlan): RedirectResponse
    {
        $treatmentPlan->update($request->validated());

        return redirect()
            ->route('treatment-plans.show', $treatmentPlan)
            ->with('success', 'Treatment plan updated successfully.');
    }

    public function destroy(TreatmentPlan $treatmentPlan): RedirectResponse
    {
        $patientId = $treatmentPlan->patient_id;
        $treatmentPlan->delete();

        return redirect()
            ->route('patients.show', $patientId)
            ->with('success', 'Treatment plan deleted successfully.');
    }

    // Additional helper methods

    public function updateStatus(TreatmentPlan $treatmentPlan, string $status): RedirectResponse
    {
        $validStatuses = ['draft', 'presented', 'accepted', 'rejected', 'in_progress', 'completed', 'cancelled'];

        if (! in_array($status, $validStatuses)) {
            return back()->with('error', 'Invalid status.');
        }

        $updates = ['status' => $status];

        // Auto-fill dates based on status
        if ($status === 'presented' && ! $treatmentPlan->presented_date) {
            $updates['presented_date'] = now();
        }

        if ($status === 'accepted' && ! $treatmentPlan->accepted_date) {
            $updates['accepted_date'] = now();
        }

        if ($status === 'in_progress' && ! $treatmentPlan->start_date) {
            $updates['start_date'] = now();
        }

        if ($status === 'completed' && ! $treatmentPlan->completion_date) {
            $updates['completion_date'] = now();
        }

        $treatmentPlan->update($updates);

        return back()->with('success', 'Treatment plan status updated successfully.');
    }

    public function addItem(TreatmentPlan $treatmentPlan): RedirectResponse
    {
        $validated = request()->validate([
            'treatment_id' => 'required|exists:treatments,id',
            'tooth_number' => 'nullable|string',
            'tooth_surface' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1',
            'insurance_estimate' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $treatment = Treatment::find($validated['treatment_id']);
        $quantity = $validated['quantity'] ?? 1;

        TreatmentPlanItem::create([
            'treatment_plan_id' => $treatmentPlan->id,
            'treatment_id' => $validated['treatment_id'],
            'tooth_number' => $validated['tooth_number'] ?? null,
            'tooth_surface' => $validated['tooth_surface'] ?? null,
            'quantity' => $quantity,
            'unit_cost' => $treatment->price,
            'total_cost' => $treatment->price * $quantity,
            'insurance_estimate' => $validated['insurance_estimate'] ?? 0,
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Treatment added to plan successfully.');
    }

    public function removeItem(TreatmentPlan $treatmentPlan, TreatmentPlanItem $item): RedirectResponse
    {
        if ($item->treatment_plan_id !== $treatmentPlan->id) {
            return back()->with('error', 'Invalid treatment plan item.');
        }

        $item->delete();

        return back()->with('success', 'Treatment removed from plan successfully.');
    }

    public function updateItemStatus(TreatmentPlan $treatmentPlan, TreatmentPlanItem $item, string $status): RedirectResponse
    {
        if ($item->treatment_plan_id !== $treatmentPlan->id) {
            return back()->with('error', 'Invalid treatment plan item.');
        }

        $validStatuses = ['pending', 'in_progress', 'completed', 'cancelled'];

        if (! in_array($status, $validStatuses)) {
            return back()->with('error', 'Invalid status.');
        }

        if ($status === 'completed') {
            $item->markAsCompleted();
        } elseif ($status === 'in_progress') {
            $item->markAsInProgress();
        } elseif ($status === 'cancelled') {
            $item->markAsCancelled();
        } else {
            $item->update(['status' => $status]);
        }

        return back()->with('success', 'Treatment item status updated successfully.');
    }

    public function sendEmail(TreatmentPlan $treatmentPlan): RedirectResponse
    {
        try {
            Mail::to($treatmentPlan->patient->email)
                ->send(new TreatmentPlanMail($treatmentPlan));

            return back()->with('success', 'Treatment plan emailed to '.$treatmentPlan->patient->full_name.' successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: '.$e->getMessage());
        }
    }
}
