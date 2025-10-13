<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_name',
        'plan_description',
        'amount',
        'currency',
        'billing_cycle',
        'start_date',
        'end_date',
        'renewal_date',
        'status',
        'payment_method',
        'transaction_id',
        'auto_renewal',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'renewal_date' => 'date',
            'amount' => 'decimal:2',
            'auto_renewal' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->end_date->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->end_date->isPast();
    }

    public function daysUntilRenewal(): int
    {
        return now()->diffInDays($this->renewal_date, false);
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'active' => 'green',
            'expired' => 'red',
            'cancelled' => 'gray',
            'pending' => 'yellow',
            default => 'gray',
        };
    }

    public function getFormattedAmountAttribute(): string
    {
        return $this->currency.' '.number_format($this->amount, 2);
    }
}
