<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelSupply extends Model
{
    protected $table = 'fuel_supplies';

    protected $fillable = [
        'vehicle_id',
        'driver_user_id',
        'liters',
        'price_per_liter',
        'total_value',
        'odometer',
        'coupon_number',
        'fuel_type',
        'supply_date',
    ];

    protected $casts = [
        'liters' => 'decimal:2',
        'price_per_liter' => 'decimal:4',
        'total_value' => 'decimal:2',
        'odometer' => 'integer',
        'supply_date' => 'date',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_user_id');
    }
}
