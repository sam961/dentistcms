<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'appointment_id',
        'dentist_id',
        'visit_date',
        'chief_complaint',
        'clinical_findings',
        'diagnosis',
        'treatment_provided',
        'prescription',
        'recommendations',
        'next_visit_date',
        'tooth_chart',
        'xray_images',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'next_visit_date' => 'date',
        'tooth_chart' => 'array',
        'xray_images' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function dentist()
    {
        return $this->belongsTo(Dentist::class);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('visit_date', 'desc');
    }
}
