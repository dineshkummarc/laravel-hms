<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'department_id',
        'parent_appointment_id', // Maps the parent consultation for follow-ups
        'appointment_date',
        'token_number',
        'visit_type',
        'status',
        'guest_name',
        'guest_phone',
        'reason',
        'symptoms_summary',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    // Self-Referential Relationship: Points to the parent appointment
    public function originalAppointment(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_appointment_id');
    }

    // Pulls subsequent follow-up chains
    public function followUps(): HasMany
    {
        return $this->hasMany(self::class, 'parent_appointment_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function medicalRecord(): HasOne
    {
        return $this->hasOne(MedicalRecord::class);
    }

    // Local Query Scopes for performance offloading
    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('status', 'confirmed');
    }
}
