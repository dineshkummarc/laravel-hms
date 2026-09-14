<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BloodUnit extends Model
{
    protected $fillable = [
        'bag_number', 'blood_group', 'component', 'units', 'donor_name',
        'donor_phone', 'collected_on', 'expires_on', 'status',
    ];

    protected $casts = [
        'collected_on' => 'date',
        'expires_on' => 'date',
    ];

    public function requests(): HasMany
    {
        return $this->hasMany(BloodRequest::class, 'fulfilled_unit_id');
    }
}
