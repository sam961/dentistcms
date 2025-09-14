<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dentist extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'license_number',
        'specialization',
        'years_of_experience',
        'qualifications',
        'status',
        'working_hours_start',
        'working_hours_end',
        'working_days',
    ];

    protected $casts = [
        'working_days' => 'array',
        'working_hours_start' => 'datetime:H:i',
        'working_hours_end' => 'datetime:H:i',
    ];

    protected $appends = ['full_name'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function isAvailable()
    {
        return $this->status === 'active';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
