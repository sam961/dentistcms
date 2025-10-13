<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();

            // Clinic Information
            $table->string('name'); // Clinic name
            $table->string('subdomain')->unique(); // salam.dentistcms.com
            $table->string('custom_domain')->nullable()->unique(); // Future: custom domains

            // Owner Information
            $table->string('owner_name');
            $table->string('owner_email');
            $table->string('owner_phone')->nullable();

            // Clinic Details
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Saudi Arabia');
            $table->string('logo')->nullable(); // Path to clinic logo

            // Status & Subscription
            $table->enum('status', ['trial', 'active', 'suspended', 'cancelled'])->default('trial');
            $table->unsignedBigInteger('subscription_id')->nullable();

            // Subscription Limits
            $table->integer('max_users')->default(5);
            $table->integer('max_patients')->default(100);
            $table->integer('max_dentists')->default(3);

            // Trial & Dates
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
            $table->json('data')->nullable(); // For additional custom data
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
