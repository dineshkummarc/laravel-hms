<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodRequest extends Model
{
    protected $fillable = [
        'patient_id', 'requester_name', 'blood_group', 'component',
        'units_needed', 'urgency', 'status', 'reason', 'fulfilled_unit_id',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function fulfilledUnit(): BelongsTo
    {
        return $this->belongsTo(BloodUnit::class, 'fulfilled_unit_id');
    }
}
