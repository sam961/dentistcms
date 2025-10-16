<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerioMeasurement extends Model
{
    protected $fillable = [
        'perio_chart_id',
        'tooth_number',
        // Pocket Depth
        'pd_mb', 'pd_b', 'pd_db', 'pd_ml', 'pd_l', 'pd_dl',
        // Gingival Margin
        'gm_mb', 'gm_b', 'gm_db', 'gm_ml', 'gm_l', 'gm_dl',
        // Bleeding on Probing
        'bop_mb', 'bop_b', 'bop_db', 'bop_ml', 'bop_l', 'bop_dl',
        // Plaque Index
        'plaque_mb', 'plaque_b', 'plaque_db', 'plaque_ml', 'plaque_l', 'plaque_dl',
        // Other measurements
        'mobility',
        'furcation',
        'missing',
        'implant',
    ];

    protected $casts = [
        'bop_mb' => 'boolean',
        'bop_b' => 'boolean',
        'bop_db' => 'boolean',
        'bop_ml' => 'boolean',
        'bop_l' => 'boolean',
        'bop_dl' => 'boolean',
        'plaque_mb' => 'boolean',
        'plaque_b' => 'boolean',
        'plaque_db' => 'boolean',
        'plaque_ml' => 'boolean',
        'plaque_l' => 'boolean',
        'plaque_dl' => 'boolean',
        'missing' => 'boolean',
        'implant' => 'boolean',
        'mobility' => 'integer',
        'furcation' => 'integer',
    ];

    /**
     * Get the perio chart that owns the measurement.
     */
    public function perioChart(): BelongsTo
    {
        return $this->belongsTo(PerioChart::class);
    }

    /**
     * Get the maximum pocket depth for this tooth.
     */
    public function getMaxPocketDepthAttribute(): ?int
    {
        $depths = collect([
            $this->pd_mb,
            $this->pd_b,
            $this->pd_db,
            $this->pd_ml,
            $this->pd_l,
            $this->pd_dl,
        ])->filter();

        return $depths->isNotEmpty() ? $depths->max() : null;
    }

    /**
     * Get the average pocket depth for this tooth.
     */
    public function getAveragePocketDepthAttribute(): float
    {
        $depths = collect([
            $this->pd_mb,
            $this->pd_b,
            $this->pd_db,
            $this->pd_ml,
            $this->pd_l,
            $this->pd_dl,
        ])->filter();

        return $depths->isNotEmpty() ? round($depths->average(), 1) : 0;
    }

    /**
     * Get the clinical attachment level (CAL) for each point.
     * CAL = Pocket Depth + Gingival Margin
     */
    public function getCalMbAttribute(): ?int
    {
        return $this->pd_mb && $this->gm_mb ? $this->pd_mb + $this->gm_mb : null;
    }

    public function getCalBAttribute(): ?int
    {
        return $this->pd_b && $this->gm_b ? $this->pd_b + $this->gm_b : null;
    }

    public function getCalDbAttribute(): ?int
    {
        return $this->pd_db && $this->gm_db ? $this->pd_db + $this->gm_db : null;
    }

    public function getCalMlAttribute(): ?int
    {
        return $this->pd_ml && $this->gm_ml ? $this->pd_ml + $this->gm_ml : null;
    }

    public function getCalLAttribute(): ?int
    {
        return $this->pd_l && $this->gm_l ? $this->pd_l + $this->gm_l : null;
    }

    public function getCalDlAttribute(): ?int
    {
        return $this->pd_dl && $this->gm_dl ? $this->pd_dl + $this->gm_dl : null;
    }

    /**
     * Check if this tooth has bleeding.
     */
    public function hasBleedingAttribute(): bool
    {
        return $this->bop_mb || $this->bop_b || $this->bop_db ||
               $this->bop_ml || $this->bop_l || $this->bop_dl;
    }

    /**
     * Check if this tooth has plaque.
     */
    public function hasPlaqueAttribute(): bool
    {
        return $this->plaque_mb || $this->plaque_b || $this->plaque_db ||
               $this->plaque_ml || $this->plaque_l || $this->plaque_dl;
    }

    /**
     * Get the severity level for this tooth based on pocket depth.
     */
    public function getSeverityLevelAttribute(): string
    {
        if ($this->missing || $this->implant) {
            return 'none';
        }

        $maxDepth = $this->max_pocket_depth;

        if ($maxDepth >= 7) {
            return 'severe';
        } elseif ($maxDepth >= 5) {
            return 'moderate';
        } elseif ($maxDepth >= 4) {
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
            'severe' => '#DC2626', // red-600
            'moderate' => '#F59E0B', // amber-500
            'mild' => '#FCD34D', // yellow-300
            'healthy' => '#10B981', // green-500
            default => '#9CA3AF', // gray-400
        };
    }

    /**
     * Get mobility description.
     */
    public function getMobilityDescriptionAttribute(): string
    {
        return match ($this->mobility) {
            1 => 'Slight (Class I)',
            2 => 'Moderate (Class II)',
            3 => 'Severe (Class III)',
            default => 'None',
        };
    }

    /**
     * Get furcation description.
     */
    public function getFurcationDescriptionAttribute(): string
    {
        return match ($this->furcation) {
            1 => 'Class I',
            2 => 'Class II',
            3 => 'Class III',
            default => 'None',
        };
    }
}
