<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerioChart extends Model
{
    protected $fillable = [
        'patient_id',
        'dentist_id',
        'tenant_id',
        'chart_date',
        'notes',
        'chart_type',
    ];

    protected $casts = [
        'chart_date' => 'date',
    ];

    protected $appends = [
        'bleeding_percentage',
        'plaque_percentage',
        'average_pocket_depth',
        'severity_level',
    ];

    /**
     * Get the patient that owns the perio chart.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the dentist that created the perio chart.
     */
    public function dentist(): BelongsTo
    {
        return $this->belongsTo(Dentist::class);
    }

    /**
     * Get the tenant that owns the perio chart.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the measurements for the perio chart.
     */
    public function measurements(): HasMany
    {
        return $this->hasMany(PerioMeasurement::class);
    }

    /**
     * Get the bleeding on probing percentage.
     */
    public function getBleedingPercentageAttribute(): float
    {
        $measurements = $this->measurements;
        if ($measurements->isEmpty()) {
            return 0;
        }

        $totalPoints = 0;
        $bleedingPoints = 0;

        foreach ($measurements as $measurement) {
            if (! $measurement->missing && ! $measurement->implant) {
                $totalPoints += 6;
                $bleedingPoints += collect([
                    $measurement->bop_mb,
                    $measurement->bop_b,
                    $measurement->bop_db,
                    $measurement->bop_ml,
                    $measurement->bop_l,
                    $measurement->bop_dl,
                ])->filter()->count();
            }
        }

        return $totalPoints > 0 ? round(($bleedingPoints / $totalPoints) * 100, 1) : 0;
    }

    /**
     * Get the plaque index percentage.
     */
    public function getPlaquePercentageAttribute(): float
    {
        $measurements = $this->measurements;
        if ($measurements->isEmpty()) {
            return 0;
        }

        $totalPoints = 0;
        $plaquePoints = 0;

        foreach ($measurements as $measurement) {
            if (! $measurement->missing && ! $measurement->implant) {
                $totalPoints += 6;
                $plaquePoints += collect([
                    $measurement->plaque_mb,
                    $measurement->plaque_b,
                    $measurement->plaque_db,
                    $measurement->plaque_ml,
                    $measurement->plaque_l,
                    $measurement->plaque_dl,
                ])->filter()->count();
            }
        }

        return $totalPoints > 0 ? round(($plaquePoints / $totalPoints) * 100, 1) : 0;
    }

    /**
     * Get the average pocket depth.
     */
    public function getAveragePocketDepthAttribute(): float
    {
        $measurements = $this->measurements;
        if ($measurements->isEmpty()) {
            return 0;
        }

        $totalDepth = 0;
        $totalPoints = 0;

        foreach ($measurements as $measurement) {
            if (! $measurement->missing && ! $measurement->implant) {
                $depths = collect([
                    $measurement->pd_mb,
                    $measurement->pd_b,
                    $measurement->pd_db,
                    $measurement->pd_ml,
                    $measurement->pd_l,
                    $measurement->pd_dl,
                ])->filter();

                $totalDepth += $depths->sum();
                $totalPoints += $depths->count();
            }
        }

        return $totalPoints > 0 ? round($totalDepth / $totalPoints, 1) : 0;
    }

    /**
     * Get the severity level based on average pocket depth.
     */
    public function getSeverityLevelAttribute(): string
    {
        $avgDepth = $this->average_pocket_depth;

        if ($avgDepth >= 6) {
            return 'severe';
        } elseif ($avgDepth >= 4) {
            return 'moderate';
        } elseif ($avgDepth >= 3) {
            return 'mild';
        }

        return 'healthy';
    }

    /**
     * Get the color for severity level.
     */
    public function getSeverityColorAttribute(): string
    {
        return match ($this->severity_level) {
            'severe' => 'red',
            'moderate' => 'orange',
            'mild' => 'yellow',
            default => 'green',
        };
    }

    /**
     * Get tooth numbers based on chart type.
     */
    public function getToothNumbers(): array
    {
        if ($this->chart_type === 'primary') {
            // Primary (deciduous) teeth: A-T (lettered)
            return range(1, 20);
        }

        // Adult teeth: 1-32
        return range(1, 32);
    }

    /**
     * Create measurements for all teeth in the chart.
     */
    public function createMeasurementsForAllTeeth(): void
    {
        $toothNumbers = $this->getToothNumbers();

        foreach ($toothNumbers as $toothNumber) {
            $this->measurements()->firstOrCreate([
                'tooth_number' => $toothNumber,
            ]);
        }
    }
}
