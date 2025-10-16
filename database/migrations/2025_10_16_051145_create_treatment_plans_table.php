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
        Schema::create('treatment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dentist_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('phase', ['immediate', 'soon', 'future', 'optional'])->default('immediate');
            $table->enum('status', ['draft', 'presented', 'accepted', 'rejected', 'in_progress', 'completed', 'cancelled'])->default('draft');
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->decimal('insurance_coverage', 10, 2)->default(0);
            $table->decimal('patient_portion', 10, 2)->default(0);
            $table->date('presented_date')->nullable();
            $table->date('accepted_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->text('notes')->nullable();
            $table->integer('priority')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'status']);
            $table->index(['dentist_id', 'status']);
            $table->index('phase');
        });

        Schema::create('treatment_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('treatment_id')->constrained()->cascadeOnDelete();
            $table->string('tooth_number')->nullable();
            $table->string('tooth_surface')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->decimal('insurance_estimate', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->date('completed_date')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index('treatment_plan_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_plan_items');
        Schema::dropIfExists('treatment_plans');
    }
};
