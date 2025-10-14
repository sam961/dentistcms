<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantSubscriptionHistory extends Model
{
    protected $fillable = [
        'tenant_id',
        'action',
        'subscription_tier',
        'amount',
        'billing_cycle',
        'starts_at',
        'ends_at',
        'status',
        'notes',
        'performed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
