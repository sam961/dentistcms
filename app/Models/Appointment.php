<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'dentist_id',
        'appointment_date',
        'appointment_time',
        'duration',
        'status',
        'type',
        'reason',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist()
    {
        return $this->belongsTo(Dentist::class);
    }

    public function treatments()
    {
        return $this->belongsToMany(Treatment::class, 'appointment_treatments')
            ->withPivot('quantity', 'price', 'notes')
            ->withTimestamps();
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now()->toDateString())
            ->where('status', '!=', 'cancelled');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', now()->toDateString());
    }

    public function getFullDateTimeAttribute()
    {
        return $this->appointment_date->format('Y-m-d') . ' ' . $this->appointment_time;
    }
}
