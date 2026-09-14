<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class Invoice extends Model
{
    protected $fillable = [
        'patient_id',
        'subtotal',
        'tax',
        'discount',
        'total',
        'status',
        'line_items',
    ];

    protected $casts = [
        'line_items' => AsArrayObject::class,
        'subtotal'   => 'decimal:2',
        'tax'        => 'decimal:2',
        'discount'   => 'decimal:2',
        'total'      => 'decimal:2',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function labReports(): HasMany
    {
        return $this->hasMany(LabReport::class);
    }

    // Accessor computing dynamic real-time pending balance balances
    public function getRemainingBalanceAttribute(): float
    {
        $paid = $this->payments()->where('status', 'completed')->sum('amount');
        return max(0, $this->total - $paid);
    }
}
