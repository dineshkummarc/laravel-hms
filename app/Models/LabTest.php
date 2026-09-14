<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabTest extends Model
{
    protected $fillable = [
        'name',
        'department_id',
        'cost',
        'slug',
        'is_active'
    ];

    protected $casts = [
        'cost' => 'decimal:2',
    ];

    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
}
