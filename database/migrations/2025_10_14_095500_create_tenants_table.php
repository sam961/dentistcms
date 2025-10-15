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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Clinic name
            $table->string('subdomain')->unique()->nullable(); // For subdomain access
            $table->string('domain')->unique()->nullable(); // Custom domain
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->string('subscription_status')->default('trial'); // trial, active, expired, cancelled
            $table->string('subscription_tier')->nullable(); // basic, pro, enterprise
            $table->decimal('subscription_amount', 10, 2)->nullable();
            $table->string('billing_cycle')->nullable(); // monthly, quarterly, yearly
            $table->timestamp('subscription_starts_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->timestamp('next_billing_date')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
