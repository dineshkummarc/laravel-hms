<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class LabReport extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'invoice_id',
        'lab_test_id',
        'status',
        'results',
        'document_path',
        'technician_id',
        'handed_over_at',
        'handed_over_by',
    ];

    protected $casts = [
        'results'        => AsArrayObject::class,
        'handed_over_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function labTest(): BelongsTo
    {
        return $this->belongsTo(LabTest::class);
    }

    public function handler(): BelongsTo
    {
        // Tracks physical Report Giver identity via a User schema alias
        return $this->belongsTo(User::class, 'handed_over_by');
    }
}
