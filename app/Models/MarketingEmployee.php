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
     * Boot method to auto-generate employee code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            if (empty($employee->employee_code)) {
                $employee->employee_code = self::generateEmployeeCode();
            }
        });
    }

    /**
     * Generate unique employee code
     */
    private static function generateEmployeeCode(): string
    {
        do {
            // Format: ME-YYYY-XXXX (Marketing Employee - Year - Sequential Number)
            $year = date('Y');
            $lastEmployee = self::whereYear('created_at', $year)
                ->orderBy('id', 'desc')
                ->first();

            if ($lastEmployee && preg_match('/ME-'.$year.'-(\d+)/', $lastEmployee->employee_code, $matches)) {
                $sequential = intval($matches[1]) + 1;
            } else {
                $sequential = 1;
            }

            $code = sprintf('ME-%s-%04d', $year, $sequential);
        } while (self::where('employee_code', $code)->exists());

        return $code;
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
