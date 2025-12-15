<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakdownReport extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'machine_id',
        'reporter_id',
        'reporter_name',
        'department',
        'line',
        'shift',
        'machine_number',
        'problem_area',
        'status',
        'reported_at',
        'repair_start_at',
        'repair_end_at',
        'maintenance_leader_id',
        'machine_operational',
        'technician_notes',
        'maintenance_classification',
        'rank',
        'design_source',
        'repair_action',
        'responsibility',
        'responsibility_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reported_at' => 'datetime',
            'repair_start_at' => 'datetime',
            'repair_end_at' => 'datetime',
        ];
    }

    /**
     * Relationship dengan mesin
     */
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    /**
     * Relationship dengan pelapor
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Relationship dengan leader teknisi
     */
    public function maintenanceLeader()
    {
        return $this->belongsTo(User::class, 'maintenance_leader_id');
    }

    /**
     * Relationship dengan jenis kerusakan (many-to-many)
     */
    public function eventTypes()
    {
        return $this->belongsToMany(EventType::class, 'breakdown_events')
                    ->withTimestamps();
    }

    /**
     * Relationship dengan penyebab kerusakan (many-to-many)
     */
    public function causeTypes()
    {
        return $this->belongsToMany(CauseType::class, 'breakdown_causes')
                    ->withTimestamps();
    }

    /**
     * Relationship dengan part yang diganti
     */
    public function breakdownParts()
    {
        return $this->hasMany(BreakdownPart::class);
    }

    /**
     * Relationship dengan tanggung jawab
     */
    public function breakdownResponsibility()
    {
        return $this->hasOne(BreakdownResponsibility::class);
    }

    /**
     * Hitung durasi perbaikan dalam menit
     */
    public function getRepairDurationAttribute()
    {
        if ($this->repair_start_at && $this->repair_end_at) {
            return $this->repair_start_at->diffInMinutes($this->repair_end_at);
        }
        return null;
    }

    /**
     * Scope untuk laporan baru
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope untuk laporan sedang diperbaiki
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope untuk laporan selesai
     */
    public function scopeDone($query)
    {
        return $query->where('status', 'done');
    }

}
