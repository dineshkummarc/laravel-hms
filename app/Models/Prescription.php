<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prescription extends Model
{
    protected $fillable = [
        'medical_record_id',
        'medications',
        'instructions',
    ];

    protected $casts = [
        'medications' => 'array', // JSON: Array of {drug, dosage, frequency, duration}
    ];

    public function medicalRecord(): BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function dispenses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Dispense::class);
    }
}
