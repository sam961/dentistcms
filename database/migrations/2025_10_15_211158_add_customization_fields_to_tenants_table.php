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
            $table->json('enabled_features')->nullable()->after('auto_renew');
            $table->json('custom_settings')->nullable()->after('enabled_features');
            $table->json('custom_modules')->nullable()->after('custom_settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['enabled_features', 'custom_settings', 'custom_modules']);
        });
    }
};
