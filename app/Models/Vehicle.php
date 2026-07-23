<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'plate',
        'dut',
        'renavan',
        'brand_model',
        'color',
        'year_fabrication',
        'year_model',
        'is_outsourced',
        'owner_name',
        'owner_phone',
        'driver_user_id',
        'status',
    ];

    protected $casts = [
        'is_outsourced' => 'boolean',
        'year_fabrication' => 'integer',
        'year_model' => 'integer',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_user_id');
    }

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(VehicleCheckin::class);
    }
}
