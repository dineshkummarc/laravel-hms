<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BiometricDevice extends Model
{
    protected $fillable = [
        'name',
        'serial_number',
        'ip_address',
        'location',
        'api_token',
        'is_online',
        'last_ping_at',
    ];

    protected $casts = [
        'is_online'    => 'boolean',
        'last_ping_at' => 'datetime',
    ];

    public function attendanceLogs(): HasMany { return $this->hasMany(AttendanceLog::class); }
}
