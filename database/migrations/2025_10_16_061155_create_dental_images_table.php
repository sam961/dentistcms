<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dental_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('tooth_record_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tooth_number')->nullable();

            // Image metadata
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('image_type', [
                'xray_intraoral',
                'xray_panoramic',
                'xray_bitewing',
                'xray_periapical',
                'xray_cephalometric',
                'cbct',
                'photo_intraoral',
                'photo_extraoral',
                'scan_3d',
                'other',
            ])->default('other');

            // File storage
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size');
            $table->string('thumbnail_path')->nullable();

            // Image properties
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->enum('view_angle', ['frontal', 'lateral', 'occlusal', 'other'])->nullable();

            // Clinical context
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('dentist_id')->nullable()->constrained()->nullOnDelete();
            $table->date('captured_date')->nullable();
            $table->text('clinical_notes')->nullable();

            // Organization
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_before_photo')->default(false);
            $table->boolean('is_after_photo')->default(false);
            $table->foreignId('related_image_id')->nullable()->constrained('dental_images')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['tenant_id', 'patient_id']);
            $table->index(['patient_id', 'image_type']);
            $table->index('tooth_number');
            $table->index('captured_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dental_images');
    }
};
