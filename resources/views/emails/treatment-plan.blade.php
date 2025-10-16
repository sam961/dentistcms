<x-mail::message>
# Your Treatment Plan

Dear {{ $patient->full_name }},

Thank you for choosing us for your dental care. Below is a detailed overview of your personalized treatment plan.

---

## Plan Overview

**Plan Name:** {{ $treatmentPlan->title }}

**Dentist:** Dr. {{ $dentist->full_name }}

**Status:** {{ str_replace('_', ' ', ucfirst($treatmentPlan->status)) }}

**Phase:** {{ ucfirst($treatmentPlan->phase) }} Priority

@if($treatmentPlan->description)
**Description:** {{ $treatmentPlan->description }}
@endif

---

## Treatment Items

@foreach($items as $item)
### {{ $loop->iteration }}. {{ $item->treatment->name }}

@if($item->tooth_number)
- **Tooth:** #{{ $item->tooth_number }}@if($item->tooth_surface) ({{ $item->tooth_surface }} surface)@endif
@endif
- **Quantity:** {{ $item->quantity }}
- **Cost:** ${{ number_format($item->total_cost, 2) }}
- **Insurance Estimate:** ${{ number_format($item->insurance_estimate, 2) }}
- **Your Portion:** ${{ number_format($item->patient_cost, 2) }}
- **Status:** {{ ucfirst($item->status) }}

@if($item->notes)
*Note: {{ $item->notes }}*
@endif

---

@endforeach

## Financial Summary

<x-mail::table>
| Description | Amount |
|:------------|-------:|
| **Total Treatment Cost** | ${{ number_format($treatmentPlan->total_cost, 2) }} |
| **Insurance Coverage** | ${{ number_format($treatmentPlan->insurance_coverage, 2) }} |
| **Your Estimated Portion** | **${{ number_format($treatmentPlan->patient_portion, 2) }}** |
</x-mail::table>

@if($treatmentPlan->notes)
---

## Additional Notes

{{ $treatmentPlan->notes }}
@endif

---

## Important Information

- This is an estimate and actual costs may vary
- Insurance coverage is estimated and subject to verification
- Please contact us if you have any questions about your treatment plan
- We're here to help you achieve your best dental health

@if($treatmentPlan->patient->phone)
**Contact Us:** {{ $treatmentPlan->patient->phone }}
@endif

<x-mail::button url="{{ config('app.url') }}">
Visit Our Website
</x-mail::button>

Thank you for trusting us with your dental care!

Best regards,<br>
Dr. {{ $dentist->full_name }}<br>
{{ config('app.name') }}
</x-mail::message>
