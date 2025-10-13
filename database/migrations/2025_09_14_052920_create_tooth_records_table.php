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
        Schema::create('tooth_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('tooth_number'); // Universal numbering system (1-32 for permanent, A-T for primary)
            $table->string('tooth_type')->default('permanent'); // permanent or primary
            $table->string('condition')->nullable(); // healthy, cavity, filled, crown, missing, etc.
            $table->text('notes')->nullable();
            $table->date('treatment_date')->nullable();
            $table->foreignId('treatment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('dentist_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('medical_record_id')->nullable()->constrained()->nullOnDelete();
            $table->string('surface')->nullable(); // mesial, distal, occlusal, buccal, lingual
            $table->string('severity')->nullable(); // mild, moderate, severe
            $table->timestamps();

            $table->index(['patient_id', 'tooth_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tooth_records');
    }
};
