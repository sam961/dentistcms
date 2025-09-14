<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Treatment extends Model
{
    use HasFactory;

    protected $fillable = [
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
