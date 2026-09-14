<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientProfile extends Model
{
    protected $fillable = [
        'user_id',
        'age',
        'gender',
        'blood_group',
        'address',
        'emergency_contact',
        'medical_history',
    ];

    protected $casts = [
        'medical_history' => 'array', // Array of chronic conditions/allergies
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
