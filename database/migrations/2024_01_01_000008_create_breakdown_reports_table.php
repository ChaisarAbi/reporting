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
        Schema::create('breakdown_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained()->onDelete('cascade');
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->string('department');
            $table->string('line');
            $table->enum('shift', ['1', '2', '3']);
            $table->string('machine_number')->nullable();
            $table->text('problem_area');
            $table->enum('urgency', ['urgent', 'not_urgent'])->default('not_urgent');
            $table->enum('status', ['new', 'in_progress', 'done'])->default('new');
            $table->timestamp('reported_at')->useCurrent();
            $table->timestamp('repair_start_at')->nullable();
            $table->timestamp('repair_end_at')->nullable();
            $table->foreignId('maintenance_leader_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('machine_operational', ['yes', 'no'])->nullable();
            $table->text('technician_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breakdown_reports');
    }
};
