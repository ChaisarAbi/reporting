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
        // Add position column to breakdown_reports table
        Schema::table('breakdown_reports', function (Blueprint $table) {
            $table->string('position', 200)->nullable()->after('problem_area');
        });

        // Limit user name to 25 characters
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 25)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove position column from breakdown_reports
        Schema::table('breakdown_reports', function (Blueprint $table) {
            $table->dropColumn('position');
        });

        // Revert user name length (assuming original was 255)
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 255)->change();
        });
    }
};
