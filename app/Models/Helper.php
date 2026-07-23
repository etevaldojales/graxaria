<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Helper extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function collectionsAsHelper1(): HasMany
    {
        return $this->hasMany(Collection::class, 'helper_id');
    }

    public function collectionsAsHelper2(): HasMany
    {
        return $this->hasMany(Collection::class, 'helper_2_id');
    }

    public function checkinsAsHelper1(): HasMany
    {
        return $this->hasMany(VehicleCheckin::class, 'helper_id');
    }

    public function checkinsAsHelper2(): HasMany
    {
        return $this->hasMany(VehicleCheckin::class, 'helper_2_id');
    }
}
