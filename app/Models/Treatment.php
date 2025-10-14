<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'category',
        'price',
        'duration',
        'requires_followup',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'requires_followup' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'appointment_treatments')
            ->withPivot('quantity', 'price', 'notes')
            ->withTimestamps();
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
