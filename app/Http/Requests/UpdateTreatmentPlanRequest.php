<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTreatmentPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['sometimes', 'exists:patients,id'],
            'dentist_id' => ['sometimes', 'exists:dentists,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'phase' => ['sometimes', 'in:immediate,soon,future,optional'],
            'status' => ['sometimes', 'in:draft,presented,accepted,rejected,in_progress,completed,cancelled'],
            'insurance_coverage' => ['nullable', 'numeric', 'min:0'],
            'presented_date' => ['nullable', 'date'],
            'accepted_date' => ['nullable', 'date'],
            'start_date' => ['nullable', 'date'],
            'completion_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'priority' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.exists' => 'The selected patient does not exist.',
            'dentist_id.exists' => 'The selected dentist does not exist.',
            'phase.in' => 'Invalid treatment phase selected.',
            'status.in' => 'Invalid status selected.',
        ];
    }
}
