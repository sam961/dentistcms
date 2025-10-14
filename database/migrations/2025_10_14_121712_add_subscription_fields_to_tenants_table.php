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
        Schema::table('tenants', function (Blueprint $table) {
            // Subscription information
            $table->string('subscription_status')->default('trial')->after('status'); // trial, active, expired, cancelled
            $table->string('subscription_tier')->nullable()->after('subscription_status'); // basic, pro, enterprise
            $table->decimal('subscription_amount', 10, 2)->nullable()->after('subscription_tier');
            $table->string('billing_cycle')->nullable()->after('subscription_amount'); // monthly, quarterly, yearly
            $table->timestamp('subscription_starts_at')->nullable()->after('billing_cycle');
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_starts_at');
            $table->timestamp('next_billing_date')->nullable()->after('subscription_ends_at');
            $table->boolean('auto_renew')->default(true)->after('next_billing_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_status',
                'subscription_tier',
                'subscription_amount',
                'billing_cycle',
                'subscription_starts_at',
                'subscription_ends_at',
                'next_billing_date',
                'auto_renew',
            ]);
        });
    }
};
