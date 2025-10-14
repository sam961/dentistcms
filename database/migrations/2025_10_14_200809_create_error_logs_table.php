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
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('level')->default('error'); // error, warning, critical
            $table->string('type')->nullable(); // Exception type
            $table->text('message');
            $table->text('exception')->nullable(); // Full exception class name
            $table->longText('trace')->nullable(); // Stack trace
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable(); // GET, POST, etc.
            $table->text('input')->nullable(); // Request input (sanitized)
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->enum('status', ['new', 'acknowledged', 'resolved', 'ignored'])->default('new');
            $table->text('notes')->nullable(); // Admin notes
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('level');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};
