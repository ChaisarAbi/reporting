<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakdownResponsibility extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'breakdown_report_id',
        'responsibility',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'responsibility' => 'string',
        ];
    }

    /**
     * Relationship dengan laporan kerusakan
     */
    public function breakdownReport()
    {
        return $this->belongsTo(BreakdownReport::class);
    }

    /**
     * Get responsibility label in Indonesian
     */
    public function getResponsibilityLabelAttribute()
    {
        $labels = [
            'design_workshop' => 'Design/Workshop',
            'supplier_part' => 'Supplier Part',
            'production_assy' => 'Produksi/Assy',
            'operator_mtc' => 'Operator MTC',
            'other' => 'Lain-lain',
        ];

        return $labels[$this->responsibility] ?? $this->responsibility;
    }
}
