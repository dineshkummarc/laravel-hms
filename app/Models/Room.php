<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'room_number',
        'department_id',
        'type', // general, icu, ccu, cabin
    ];

    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function beds(): HasMany { return $this->hasMany(Bed::class); }
}
