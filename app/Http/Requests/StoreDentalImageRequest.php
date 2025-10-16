<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDentalImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'min:1', 'max:10'],
            'images.*' => [
                'required',
                'file',
                'mimes:jpeg,jpg,png,bmp,gif,webp',
                'max:10240',
            ],
            'image_type' => [
                'required',
                'string',
                'in:xray_intraoral,xray_panoramic,xray_bitewing,xray_periapical,xray_cephalometric,cbct,photo_intraoral,photo_extraoral,scan_3d,other',
            ],
            'tooth_number' => ['nullable', 'string'],
            'tooth_record_id' => ['nullable', 'exists:tooth_records,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'view_angle' => ['nullable', 'string', 'in:frontal,lateral,occlusal,other'],
            'appointment_id' => ['nullable', 'exists:appointments,id'],
            'dentist_id' => ['nullable', 'exists:dentists,id'],
            'captured_date' => ['nullable', 'date'],
            'clinical_notes' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'is_before_photo' => ['nullable', 'boolean'],
            'is_after_photo' => ['nullable', 'boolean'],
            'related_image_id' => ['nullable', 'exists:dental_images,id'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'images.required' => 'Please select at least one image to upload.',
            'images.*.mimes' => 'Only JPEG, PNG, BMP, GIF, and WebP images are allowed.',
            'images.*.max' => 'Each image must not exceed 10MB in size.',
            'image_type.required' => 'Please select an image type.',
            'image_type.in' => 'The selected image type is invalid.',
        ];
    }
}
