<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmbulanceRequest extends Model
{
    protected $fillable = [
        'patient_id', 'caller_name', 'caller_phone', 'pickup_address',
        'destination', 'priority', 'notes', 'status', 'vehicle_code',
        'driver_name', 'dispatched_at',
    ];

    protected $casts = [
        'dispatched_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
