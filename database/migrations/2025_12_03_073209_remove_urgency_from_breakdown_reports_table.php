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
            if (Schema::hasColumn('breakdown_reports', 'urgency')) {
                $table->dropColumn('urgency');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breakdown_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('breakdown_reports', 'urgency')) {
                $table->enum('urgency', ['urgent', 'not_urgent'])->default('not_urgent')->after('problem_area');
            }
        });
    }
};
