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
            $table->string('scheduled_tier')->nullable()->after('auto_renew');
            $table->timestamp('scheduled_change_date')->nullable()->after('scheduled_tier');
            $table->text('scheduled_change_reason')->nullable()->after('scheduled_change_date');
            $table->timestamp('grace_period_ends_at')->nullable()->after('scheduled_change_reason');
            $table->timestamp('last_payment_date')->nullable()->after('grace_period_ends_at');
            $table->string('payment_status')->nullable()->after('last_payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'scheduled_tier',
                'scheduled_change_date',
                'scheduled_change_reason',
                'grace_period_ends_at',
                'last_payment_date',
                'payment_status',
            ]);
        });
    }
};
