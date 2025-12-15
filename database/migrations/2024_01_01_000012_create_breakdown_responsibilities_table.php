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
        Schema::create('breakdown_responsibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('breakdown_report_id')->constrained()->onDelete('cascade');
            $table->enum('responsibility', [
                'design_workshop',
                'supplier_part',
                'production_assy',
                'operator_mtc',
                'other'
            ]);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breakdown_responsibilities');
    }
};
