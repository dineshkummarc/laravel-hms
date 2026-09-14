<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vital extends Model
{
    protected $fillable = [
        'patient_id', 'admission_id', 'recorded_by', 'temperature', 'pulse',
        'respiratory_rate', 'blood_pressure', 'spo2', 'weight_kg', 'notes', 'recorded_at',
    ];

    protected $casts = [
        'temperature' => 'decimal:2',
        'weight_kg' => 'decimal:2',
        'recorded_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
