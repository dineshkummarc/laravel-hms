<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicine extends Model
{
    protected $fillable = [
        'name', 'generic_name', 'category', 'unit', 'unit_price',
        'stock_qty', 'reorder_level', 'expiry_date', 'manufacturer', 'is_active',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function dispenses(): HasMany
    {
        return $this->hasMany(Dispense::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock_qty <= $this->reorder_level;
    }
}
