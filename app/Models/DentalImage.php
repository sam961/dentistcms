<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class DentalImage extends Model
{
    use BelongsToTenant, HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'patient_id',
        'tooth_record_id',
        'tooth_number',
        'title',
        'description',
        'image_type',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'thumbnail_path',
        'width',
        'height',
        'view_angle',
        'appointment_id',
        'dentist_id',
        'captured_date',
        'clinical_notes',
        'category',
        'tags',
        'is_before_photo',
        'is_after_photo',
        'related_image_id',
    ];

    protected function casts(): array
    {
        return [
            'captured_date' => 'date',
            'tags' => 'array',
            'is_before_photo' => 'boolean',
            'is_after_photo' => 'boolean',
            'file_size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function toothRecord(): BelongsTo
    {
        return $this->belongsTo(ToothRecord::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function dentist(): BelongsTo
    {
        return $this->belongsTo(Dentist::class);
    }

    public function relatedImage(): BelongsTo
    {
        return $this->belongsTo(DentalImage::class, 'related_image_id');
    }

    /**
     * Get the full URL to the image file
     */
    public function getUrlAttribute(): string
    {
        return route('patients.images.download', [
            'patient' => $this->patient_id,
            'image' => $this->id,
        ]);
    }

    /**
     * Get the full URL to the thumbnail
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (! $this->thumbnail_path) {
            return null;
        }

        return route('patients.images.thumbnail', [
            'patient' => $this->patient_id,
            'image' => $this->id,
        ]);
    }

    /**
     * Get human-readable file size
     */
    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    /**
     * Get human-readable image type
     */
    public function getImageTypeFormattedAttribute(): string
    {
        return match ($this->image_type) {
            'xray_intraoral' => 'Intraoral X-Ray',
            'xray_panoramic' => 'Panoramic X-Ray',
            'xray_bitewing' => 'Bitewing X-Ray',
            'xray_periapical' => 'Periapical X-Ray',
            'xray_cephalometric' => 'Cephalometric X-Ray',
            'cbct' => 'CBCT Scan',
            'photo_intraoral' => 'Intraoral Photo',
            'photo_extraoral' => 'Extraoral Photo',
            'scan_3d' => '3D Scan',
            default => 'Other',
        };
    }

    /**
     * Delete image files when model is deleted
     */
    protected static function booted(): void
    {
        static::deleting(function (DentalImage $image) {
            // Delete the main image file
            if ($image->file_path && Storage::disk('dental_images')->exists($image->file_path)) {
                Storage::disk('dental_images')->delete($image->file_path);
            }

            // Delete the thumbnail
            if ($image->thumbnail_path && Storage::disk('dental_images')->exists($image->thumbnail_path)) {
                Storage::disk('dental_images')->delete($image->thumbnail_path);
            }
        });
    }
}
