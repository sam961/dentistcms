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
        Schema::table('patients', function (Blueprint $table) {
            // Make email nullable and remove unique constraint
            $table->string('email')->nullable()->change();

            // Remove postal_code column
            $table->dropColumn('postal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Restore email to required with unique constraint
            $table->string('email')->nullable(false)->unique()->change();

            // Re-add postal_code column
            $table->string('postal_code')->nullable()->after('city');
        });
    }
};
