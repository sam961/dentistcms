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
        Schema::create('perio_charts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('dentist_id')->constrained()->onDelete('cascade');
            $table->foreignId('tenant_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('chart_date');
            $table->text('notes')->nullable();
            $table->enum('chart_type', ['adult', 'primary'])->default('adult');
            $table->timestamps();

            $table->index(['patient_id', 'chart_date']);
            $table->index('tenant_id');
        });

        Schema::create('perio_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perio_chart_id')->constrained()->onDelete('cascade');
            $table->integer('tooth_number');

            // Pocket Depth (6 measurements per tooth: MB, B, DB, ML, L, DL)
            $table->integer('pd_mb')->nullable()->comment('Pocket Depth - Mesio-Buccal');
            $table->integer('pd_b')->nullable()->comment('Pocket Depth - Buccal');
            $table->integer('pd_db')->nullable()->comment('Pocket Depth - Disto-Buccal');
            $table->integer('pd_ml')->nullable()->comment('Pocket Depth - Mesio-Lingual');
            $table->integer('pd_l')->nullable()->comment('Pocket Depth - Lingual');
            $table->integer('pd_dl')->nullable()->comment('Pocket Depth - Disto-Lingual');

            // Gingival Margin (6 measurements)
            $table->integer('gm_mb')->nullable()->comment('Gingival Margin - Mesio-Buccal');
            $table->integer('gm_b')->nullable()->comment('Gingival Margin - Buccal');
            $table->integer('gm_db')->nullable()->comment('Gingival Margin - Disto-Buccal');
            $table->integer('gm_ml')->nullable()->comment('Gingival Margin - Mesio-Lingual');
            $table->integer('gm_l')->nullable()->comment('Gingival Margin - Lingual');
            $table->integer('gm_dl')->nullable()->comment('Gingival Margin - Disto-Lingual');

            // Bleeding on Probing (6 points: 1 = bleeding, 0 = no bleeding)
            $table->boolean('bop_mb')->default(false);
            $table->boolean('bop_b')->default(false);
            $table->boolean('bop_db')->default(false);
            $table->boolean('bop_ml')->default(false);
            $table->boolean('bop_l')->default(false);
            $table->boolean('bop_dl')->default(false);

            // Plaque Index (6 points: 1 = plaque present, 0 = no plaque)
            $table->boolean('plaque_mb')->default(false);
            $table->boolean('plaque_b')->default(false);
            $table->boolean('plaque_db')->default(false);
            $table->boolean('plaque_ml')->default(false);
            $table->boolean('plaque_l')->default(false);
            $table->boolean('plaque_dl')->default(false);

            // Mobility (0 = none, 1 = slight, 2 = moderate, 3 = severe)
            $table->integer('mobility')->default(0);

            // Furcation Involvement (0 = none, 1 = class I, 2 = class II, 3 = class III)
            $table->integer('furcation')->default(0);

            // Tooth status
            $table->boolean('missing')->default(false);
            $table->boolean('implant')->default(false);

            $table->timestamps();

            $table->index(['perio_chart_id', 'tooth_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perio_measurements');
        Schema::dropIfExists('perio_charts');
    }
};
