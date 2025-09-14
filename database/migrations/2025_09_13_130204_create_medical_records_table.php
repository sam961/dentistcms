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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('dentist_id')->constrained()->onDelete('cascade');
            $table->date('visit_date');
            $table->text('chief_complaint');
            $table->text('clinical_findings');
            $table->text('diagnosis');
            $table->text('treatment_provided');
            $table->text('prescription')->nullable();
            $table->text('recommendations')->nullable();
            $table->date('next_visit_date')->nullable();
            $table->json('tooth_chart')->nullable();
            $table->json('xray_images')->nullable();
            $table->timestamps();
            $table->index('visit_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
