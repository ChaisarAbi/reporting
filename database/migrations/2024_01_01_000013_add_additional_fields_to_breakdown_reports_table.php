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
        Schema::table('breakdown_reports', function (Blueprint $table) {
            $table->enum('maintenance_classification', ['MISOPE', 'corrective', 'preventive', 'breakdown'])->nullable()->after('urgency');
            $table->enum('rank', ['mesin_stop', 'mesin_bisa_jalan', 'pengecualian'])->nullable()->after('maintenance_classification');
            $table->enum('design_source', ['desain_dari_luar', 'desain_dari_internal'])->nullable()->after('rank');
            $table->enum('repair_action', ['penggantian_part', 'hanya_adjust', 'overhaul', 'kaizen_mekanik', 'lain_lain'])->nullable()->after('design_source');
            $table->enum('responsibility', ['design_workshop', 'supplier_part', 'production_assy', 'operator_mtc', 'other'])->nullable()->after('repair_action');
            $table->text('responsibility_notes')->nullable()->after('responsibility');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breakdown_reports', function (Blueprint $table) {
            $table->dropColumn([
                'maintenance_classification',
                'rank',
                'design_source',
                'repair_action',
                'responsibility',
                'responsibility_notes'
            ]);
        });
    }
};
