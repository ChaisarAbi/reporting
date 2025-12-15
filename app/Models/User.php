<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship dengan laporan yang dibuat
     */
    public function breakdownReports()
    {
        return $this->hasMany(BreakdownReport::class, 'reporter_id');
    }

    /**
     * Relationship dengan laporan yang ditangani sebagai teknisi
     */
    public function maintenanceReports()
    {
        return $this->hasMany(BreakdownReport::class, 'maintenance_leader_id');
    }

    /**
     * Cek apakah user adalah leader operator
     */
    public function isLeaderOperator()
    {
        return $this->role === 'leader_operator';
    }

    /**
     * Cek apakah user adalah leader teknisi
     */
    public function isLeaderTeknisi()
    {
        return $this->role === 'leader_teknisi';
    }
}
