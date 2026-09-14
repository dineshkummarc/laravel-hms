<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceLog extends Model
{
    protected $fillable = [
        'user_id',
        'biometric_device_id',
        'punch_time',
        'punch_type', // check_in, check_out, break_start, break_end
        'verification_mode', // face, card, fingerprint, pin
        'confidence_score',
        'snapshot_path',
        'status', // pending, processed, error
        'error_message',
        'raw_payload',
    ];

    protected $casts = [
        'punch_time'       => 'datetime',
        'confidence_score' => 'decimal:2',
        'raw_payload'      => 'array',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function device(): BelongsTo { return $this->belongsTo(BiometricDevice::class, 'biometric_device_id'); }
}
