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
        // Remove the default CURRENT_TIMESTAMP from reported_at field
        // This ensures the application has full control over timestamp management
        // and prevents timezone conflicts between the database server and application
        Schema::table('breakdown_reports', function (Blueprint $table) {
            // First, remove the default constraint
            $table->timestamp('reported_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the change by adding back the default CURRENT_TIMESTAMP
        Schema::table('breakdown_reports', function (Blueprint $table) {
            $table->timestamp('reported_at')->useCurrent()->change();
        });
    }
};