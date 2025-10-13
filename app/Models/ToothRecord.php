<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToothRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'tooth_number',
        'tooth_type',
        'condition',
        'notes',
        'treatment_date',
        'treatment_id',
        'dentist_id',
        'medical_record_id',
        'surface',
        'severity',
    ];

    protected function casts(): array
    {
        return [
            'treatment_date' => 'date',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class);
    }

    public function dentist(): BelongsTo
    {
        return $this->belongsTo(Dentist::class);
    }

    public function medicalRecord(): BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public static function getToothName(string $toothNumber): string
    {
        $toothNames = [
            // Upper permanent teeth
            '1' => 'Upper Right Third Molar',
            '2' => 'Upper Right Second Molar',
            '3' => 'Upper Right First Molar',
            '4' => 'Upper Right Second Premolar',
            '5' => 'Upper Right First Premolar',
            '6' => 'Upper Right Canine',
            '7' => 'Upper Right Lateral Incisor',
            '8' => 'Upper Right Central Incisor',
            '9' => 'Upper Left Central Incisor',
            '10' => 'Upper Left Lateral Incisor',
            '11' => 'Upper Left Canine',
            '12' => 'Upper Left First Premolar',
            '13' => 'Upper Left Second Premolar',
            '14' => 'Upper Left First Molar',
            '15' => 'Upper Left Second Molar',
            '16' => 'Upper Left Third Molar',
            // Lower permanent teeth
            '17' => 'Lower Left Third Molar',
            '18' => 'Lower Left Second Molar',
            '19' => 'Lower Left First Molar',
            '20' => 'Lower Left Second Premolar',
            '21' => 'Lower Left First Premolar',
            '22' => 'Lower Left Canine',
            '23' => 'Lower Left Lateral Incisor',
            '24' => 'Lower Left Central Incisor',
            '25' => 'Lower Right Central Incisor',
            '26' => 'Lower Right Lateral Incisor',
            '27' => 'Lower Right Canine',
            '28' => 'Lower Right First Premolar',
            '29' => 'Lower Right Second Premolar',
            '30' => 'Lower Right First Molar',
            '31' => 'Lower Right Second Molar',
            '32' => 'Lower Right Third Molar',
            // Primary teeth (children)
            'A' => 'Upper Right Second Molar (Primary)',
            'B' => 'Upper Right First Molar (Primary)',
            'C' => 'Upper Right Canine (Primary)',
            'D' => 'Upper Right Lateral Incisor (Primary)',
            'E' => 'Upper Right Central Incisor (Primary)',
            'F' => 'Upper Left Central Incisor (Primary)',
            'G' => 'Upper Left Lateral Incisor (Primary)',
            'H' => 'Upper Left Canine (Primary)',
            'I' => 'Upper Left First Molar (Primary)',
            'J' => 'Upper Left Second Molar (Primary)',
            'K' => 'Lower Left Second Molar (Primary)',
            'L' => 'Lower Left First Molar (Primary)',
            'M' => 'Lower Left Canine (Primary)',
            'N' => 'Lower Left Lateral Incisor (Primary)',
            'O' => 'Lower Left Central Incisor (Primary)',
            'P' => 'Lower Right Central Incisor (Primary)',
            'Q' => 'Lower Right Lateral Incisor (Primary)',
            'R' => 'Lower Right Canine (Primary)',
            'S' => 'Lower Right First Molar (Primary)',
            'T' => 'Lower Right Second Molar (Primary)',
        ];

        return $toothNames[$toothNumber] ?? 'Unknown Tooth';
    }

    public static function getConditionColor(string $condition): string
    {
        return match ($condition) {
            'healthy' => 'green',
            'cavity' => 'red',
            'filled' => 'blue',
            'crown' => 'purple',
            'root_canal' => 'orange',
            'missing' => 'gray',
            'implant' => 'indigo',
            default => 'gray',
        };
    }
}
