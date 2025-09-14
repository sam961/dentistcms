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
        Schema::create('dentists', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('license_number')->unique();
            $table->string('specialization');
            $table->integer('years_of_experience');
            $table->text('qualifications')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->time('working_hours_start')->default('09:00');
            $table->time('working_hours_end')->default('17:00');
            $table->json('working_days')->default('["monday","tuesday","wednesday","thursday","friday"]');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dentists');
    }
};
