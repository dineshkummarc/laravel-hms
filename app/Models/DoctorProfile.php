<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'specialty',
        'license_number',
        'qualification',
        'experience',
        'room_number',
        'consultation_fee',
        'bio',
    ];

    protected $casts = [
        'consultation_fee' => 'decimal:2',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
}
