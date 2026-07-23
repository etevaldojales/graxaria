<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleCheckin extends Model
{
    protected $table = 'vehicle_checkins';

    protected $fillable = [
        'vehicle_id',
        'driver_user_id',
        'helper_id',
        'helper_2_id',
        'odometer_start',
        'odometer_end',
        'check_tires',
        'check_brakes',
        'check_lights',
        'check_oil',
        'check_wipers',
        'num_drums',
        'is_impeditivo',
        'obs',
        'check_date',
        'checkout_date',
    ];

    protected $casts = [
        'odometer_start' => 'integer',
        'odometer_end' => 'integer',
        'check_tires' => 'boolean',
        'check_brakes' => 'boolean',
        'check_lights' => 'boolean',
        'check_oil' => 'boolean',
        'check_wipers' => 'boolean',
        'is_impeditivo' => 'boolean',
        'check_date' => 'date',
        'checkout_date' => 'datetime',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_user_id');
    }

    public function helper(): BelongsTo
    {
        return $this->belongsTo(Helper::class, 'helper_id');
    }

    public function helper2(): BelongsTo
    {
        return $this->belongsTo(Helper::class, 'helper_2_id');
    }
}
