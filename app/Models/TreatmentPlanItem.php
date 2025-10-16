<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentPlanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_plan_id',
        'treatment_id',
        'tooth_number',
        'tooth_surface',
        'quantity',
        'unit_cost',
        'total_cost',
        'insurance_estimate',
        'notes',
        'status',
        'completed_date',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'unit_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'insurance_estimate' => 'decimal:2',
            'completed_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (TreatmentPlanItem $item) {
            // Auto-calculate total cost if not set
            if (! $item->total_cost) {
                $item->total_cost = $item->unit_cost * $item->quantity;
            }

            // Set order if not set
            if ($item->order === 0 || $item->order === null) {
                $maxOrder = self::where('treatment_plan_id', $item->treatment_plan_id)->max('order') ?? 0;
                $item->order = $maxOrder + 1;
            }
        });

        static::updating(function (TreatmentPlanItem $item) {
            // Recalculate total cost when quantity or unit cost changes
            if ($item->isDirty(['quantity', 'unit_cost'])) {
                $item->total_cost = $item->unit_cost * $item->quantity;
            }
        });

        static::saved(function (TreatmentPlanItem $item) {
            // Trigger recalculation of treatment plan totals
            $item->treatmentPlan->recalculateTotals();
        });

        static::deleted(function (TreatmentPlanItem $item) {
            // Trigger recalculation of treatment plan totals
            $item->treatmentPlan->recalculateTotals();
        });
    }

    // Relationships
    public function treatmentPlan()
    {
        return $this->belongsTo(TreatmentPlan::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    // Helper methods
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'gray',
            'in_progress' => 'yellow',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    public function getPatientCostAttribute(): float
    {
        return (float) ($this->total_cost - $this->insurance_estimate);
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_date' => now(),
        ]);
    }

    public function markAsInProgress(): void
    {
        $this->update(['status' => 'in_progress']);
    }

    public function markAsCancelled(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}
