<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create temporary table to store unique part types
        DB::statement('CREATE TEMPORARY TABLE temp_part_types AS SELECT MIN(id) as id, name FROM part_types GROUP BY name');
        
        // Get all duplicate IDs to delete
        $duplicateIds = DB::table('part_types')
            ->select('part_types.id')
            ->leftJoin('temp_part_types', function($join) {
                $join->on('part_types.id', '=', 'temp_part_types.id');
            })
            ->whereNull('temp_part_types.id')
            ->pluck('id');
        
        // Update foreign keys in breakdown_parts table to point to the kept records
        foreach ($duplicateIds as $duplicateId) {
            // Find the kept record for this name
            $partType = DB::table('part_types')->find($duplicateId);
            if ($partType) {
                $keptRecord = DB::table('temp_part_types')
                    ->where('name', $partType->name)
                    ->first();
                
                if ($keptRecord) {
                    // Update breakdown_parts to use the kept record
                    DB::table('breakdown_parts')
                        ->where('part_type_id', $duplicateId)
                        ->update(['part_type_id' => $keptRecord->id]);
                }
            }
        }
        
        // Now delete the duplicate records
        if ($duplicateIds->count() > 0) {
            DB::table('part_types')->whereIn('id', $duplicateIds)->delete();
        }
        
        // Drop temporary table
        DB::statement('DROP TABLE IF EXISTS temp_part_types');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be reversed safely
        // We'll just leave it as is since it's a cleanup operation
    }
};
