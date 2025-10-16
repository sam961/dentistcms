<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TreatmentPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'patient_id',
        'dentist_id',
        'title',
        'description',
        'phase',
        'status',
        'total_cost',
        'insurance_coverage',
        'patient_portion',
        'presented_date',
        'accepted_date',
        'start_date',
        'completion_date',
        'notes',
        'priority',
    ];

    protected function casts(): array
    {
        return [
            'total_cost' => 'decimal:2',
            'insurance_coverage' => 'decimal:2',
            'patient_portion' => 'decimal:2',
            'presented_date' => 'date',
            'accepted_date' => 'date',
            'start_date' => 'date',
            'completion_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function (TreatmentPlan $plan) {
            if (! $plan->tenant_id) {
                $plan->tenant_id = auth()->user()->tenant_id;
            }
        });

        static::saved(function (TreatmentPlan $plan) {
            $plan->recalculateTotals();
        });
    }

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist()
    {
        return $this->belongsTo(Dentist::class);
    }

    public function items()
    {
        return $this->hasMany(TreatmentPlanItem::class)->orderBy('order');
    }

    // Helper methods
    public function recalculateTotals(): void
    {
        $items = $this->items;

        $this->total_cost = $items->sum('total_cost');
        $this->insurance_coverage = $items->sum('insurance_estimate');
        $this->patient_portion = $this->total_cost - $this->insurance_coverage;

        $this->saveQuietly();
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'gray',
            'presented' => 'blue',
            'accepted' => 'green',
            'rejected' => 'red',
            'in_progress' => 'yellow',
            'completed' => 'green',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    public function getPhaseColorAttribute(): string
    {
        return match ($this->phase) {
            'immediate' => 'red',
            'soon' => 'orange',
            'future' => 'blue',
            'optional' => 'gray',
            default => 'gray',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        $color = $this->status_color;
        $label = ucfirst($this->status);

        return "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{$color}-100 text-{$color}-800'>{$label}</span>";
    }

    public function getPhaseBadgeAttribute(): string
    {
        $color = $this->phase_color;
        $label = ucfirst($this->phase);

        return "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{$color}-100 text-{$color}-800'>{$label}</span>";
    }

    public function getProgressPercentageAttribute(): int
    {
        $totalItems = $this->items()->count();
        if ($totalItems === 0) {
            return 0;
        }

        $completedItems = $this->items()->where('status', 'completed')->count();

        return (int) round(($completedItems / $totalItems) * 100);
    }

    // Scopes
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPhase($query, $phase)
    {
        return $query->where('phase', $phase);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['draft', 'presented', 'accepted', 'in_progress']);
    }
}
