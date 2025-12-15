<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'line',
        'machine_number',
        'description',
    ];

    /**
     * Relationship dengan laporan kerusakan
     */
    public function breakdownReports()
    {
        return $this->hasMany(BreakdownReport::class);
    }
}
