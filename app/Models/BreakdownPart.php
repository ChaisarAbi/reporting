<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakdownPart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'breakdown_report_id',
        'part_type_id',
        'quantity',
        'notes',
    ];

    /**
     * Relationship dengan laporan kerusakan
     */
    public function breakdownReport()
    {
        return $this->belongsTo(BreakdownReport::class);
    }

    /**
     * Relationship dengan jenis part
     */
    public function partType()
    {
        return $this->belongsTo(PartType::class);
    }
}
