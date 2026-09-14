<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Admission extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'bed_id',
        'admission_date',
        'discharge_date',
        'status', // admitted, discharged, transferred
        'reason_for_admission',
    ];

    protected $casts = [
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
    ];

    public function patient(): BelongsTo { return $this->belongsTo(User::class, 'patient_id'); }
    public function doctor(): BelongsTo { return $this->belongsTo(User::class, 'doctor_id'); }
    public function bed(): BelongsTo { return $this->belongsTo(Bed::class); }
    public function invoice(): HasOne { return $this->hasOne(Invoice::class); }
    public function vitals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Vital::class);
    }
}
