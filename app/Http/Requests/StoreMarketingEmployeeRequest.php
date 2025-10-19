<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMarketingEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_super_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:marketing_employees,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'employee_code' => ['required', 'string', 'max:50', 'unique:marketing_employees,employee_code'],
            'commission_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'in:active,inactive,terminated'],
            'hire_date' => ['required', 'date'],
            'termination_date' => ['nullable', 'date', 'after_or_equal:hire_date'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Employee name is required.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'This email is already registered.',
            'employee_code.required' => 'Employee code is required.',
            'employee_code.unique' => 'This employee code is already in use.',
            'commission_percentage.required' => 'Commission percentage is required.',
            'commission_percentage.min' => 'Commission percentage must be at least 0%.',
            'commission_percentage.max' => 'Commission percentage cannot exceed 100%.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be active, inactive, or terminated.',
            'hire_date.required' => 'Hire date is required.',
            'termination_date.after_or_equal' => 'Termination date must be after or equal to hire date.',
        ];
    }
}
