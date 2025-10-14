<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update existing tenants to use new tier values
        DB::statement("UPDATE tenants SET subscription_tier = 'free' WHERE subscription_tier IS NULL OR subscription_tier = 'basic'");
        DB::statement("UPDATE tenants SET subscription_tier = 'monthly' WHERE subscription_tier = 'pro'");
        DB::statement("UPDATE tenants SET subscription_tier = 'yearly' WHERE subscription_tier = 'enterprise'");

        Schema::table('tenants', function (Blueprint $table) {
            // Add new fields for scheduled changes (check if they don't exist)
            if (!Schema::hasColumn('tenants', 'scheduled_tier')) {
                $table->string('scheduled_tier')->nullable()->after('subscription_tier');
            }
            if (!Schema::hasColumn('tenants', 'scheduled_change_date')) {
                $table->timestamp('scheduled_change_date')->nullable()->after('scheduled_tier');
            }
            if (!Schema::hasColumn('tenants', 'scheduled_change_reason')) {
                $table->text('scheduled_change_reason')->nullable()->after('scheduled_change_date');
            }

            // Add grace period and payment tracking
            if (!Schema::hasColumn('tenants', 'grace_period_ends_at')) {
                $table->timestamp('grace_period_ends_at')->nullable()->after('subscription_ends_at');
            }
            if (!Schema::hasColumn('tenants', 'last_payment_date')) {
                $table->timestamp('last_payment_date')->nullable()->after('grace_period_ends_at');
            }
            if (!Schema::hasColumn('tenants', 'payment_status')) {
                $table->string('payment_status')->default('none')->after('last_payment_date');
            }
        });

        // Remove old fields we don't need
        Schema::table('tenants', function (Blueprint $table) {
            if (Schema::hasColumn('tenants', 'subscription_amount')) {
                $table->dropColumn('subscription_amount');
            }
            if (Schema::hasColumn('tenants', 'billing_cycle')) {
                $table->dropColumn('billing_cycle');
            }
        });

        // Update history table - for SQLite just update existing data
        DB::statement("UPDATE tenant_subscription_histories SET subscription_tier = 'free' WHERE subscription_tier = 'basic'");
        DB::statement("UPDATE tenant_subscription_histories SET subscription_tier = 'monthly' WHERE subscription_tier = 'pro'");
        DB::statement("UPDATE tenant_subscription_histories SET subscription_tier = 'yearly' WHERE subscription_tier = 'enterprise'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->decimal('subscription_amount', 10, 2)->nullable();
            $table->string('billing_cycle')->nullable();

            $table->dropColumn([
                'scheduled_tier',
                'scheduled_change_date',
                'scheduled_change_reason',
                'grace_period_ends_at',
                'last_payment_date',
                'payment_status'
            ]);

            $table->enum('subscription_tier', ['basic', 'pro', 'enterprise'])->nullable()->change();
            $table->enum('subscription_status', ['trial', 'active', 'suspended', 'cancelled', 'expired'])->default('trial')->change();
        });

        Schema::table('tenant_subscription_histories', function (Blueprint $table) {
            $table->enum('subscription_tier', ['basic', 'pro', 'enterprise'])->change();
            $table->enum('action', ['created', 'updated', 'upgraded', 'downgraded', 'cancelled', 'reactivated'])->change();
        });
    }
};
