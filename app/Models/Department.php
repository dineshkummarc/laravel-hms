<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = ['name','slug'];

    public function doctors(): HasMany { return $this->hasMany(DoctorProfile::class); }
    public function rooms(): HasMany { return $this->hasMany(Room::class); }
    public function labTests(): HasMany { return $this->hasMany(LabTest::class); }
}
