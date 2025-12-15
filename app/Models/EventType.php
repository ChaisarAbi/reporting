<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category',
        'description',
    ];

    /**
     * Relationship dengan laporan kerusakan
     */
    public function breakdownReports()
    {
        return $this->belongsToMany(BreakdownReport::class, 'breakdown_events')
                    ->withTimestamps();
    }
}
