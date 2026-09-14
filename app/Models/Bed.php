<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bed extends Model
{
    protected $fillable = [
        'room_id',
        'bed_number',
        'status', // available, occupied, maintenance
        'daily_rate',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
    ];

    public function room(): BelongsTo { return $this->belongsTo(Room::class); }
}
