<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingEmployee extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'employee_code',
        'commission_percentage',
        'status',
        'hire_date',
        'termination_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'hire_date' => 'date',
            'termination_date' => 'date',
            'commission_percentage' => 'decimal:2',
        ];
    }

    /**
     * Get the tenants (clients) acquired by this marketing employee
     */
    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class, 'marketing_employee_id');
    }

    /**
     * Get active tenants count
     */
    public function getActiveTenantsCountAttribute(): int
    {
        return $this->tenants()->where('status', 'active')->count();
    }

    /**
     * Get total revenue from acquired clients
     */
    public function getTotalRevenueAttribute(): float
    {
        return $this->tenants()
            ->where('status', 'active')
            ->whereNotNull('subscription_ends_at')
            ->count() * 100; // Assuming base subscription price, can be improved
    }

    /**
     * Calculate total commissions earned
     */
    public function getTotalCommissionsAttribute(): float
    {
        return ($this->total_revenue * $this->commission_percentage) / 100;
    }
}
