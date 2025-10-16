<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTreatmentPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'dentist_id' => ['required', 'exists:dentists,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'phase' => ['required', 'in:immediate,soon,future,optional'],
            'status' => ['nullable', 'in:draft,presented,accepted,rejected,in_progress,completed,cancelled'],
            'insurance_coverage' => ['nullable', 'numeric', 'min:0'],
            'presented_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'priority' => ['nullable', 'integer', 'min:1'],

            // Treatment plan items
            'items' => ['nullable', 'array'],
            'items.*.treatment_id' => ['required', 'exists:treatments,id'],
            'items.*.tooth_number' => ['nullable', 'string', 'max:10'],
            'items.*.tooth_surface' => ['nullable', 'string', 'max:50'],
            'items.*.quantity' => ['nullable', 'integer', 'min:1'],
            'items.*.insurance_estimate' => ['nullable', 'numeric', 'min:0'],
            'items.*.notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'Please select a patient.',
            'patient_id.exists' => 'The selected patient does not exist.',
            'dentist_id.required' => 'Please select a dentist.',
            'dentist_id.exists' => 'The selected dentist does not exist.',
            'title.required' => 'Treatment plan title is required.',
            'phase.required' => 'Please select a treatment phase.',
            'phase.in' => 'Invalid treatment phase selected.',
            'items.*.treatment_id.required' => 'Please select a treatment for each item.',
            'items.*.treatment_id.exists' => 'One or more selected treatments do not exist.',
        ];
    }
}
