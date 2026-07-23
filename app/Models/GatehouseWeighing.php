<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GatehouseWeighing extends Model
{
    protected $table = 'gatehouse_weighings';

    protected $fillable = [
        'vehicle_id',
        'driver_user_id',
        'gross_weight',
        'tare_weight',
        'net_weight',
        'trip_number',
        'weighing_date',
        'status',
    ];

    protected $casts = [
        'gross_weight' => 'decimal:2',
        'tare_weight' => 'decimal:2',
        'net_weight' => 'decimal:2',
        'trip_number' => 'integer',
        'weighing_date' => 'datetime',
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
