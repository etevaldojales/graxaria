<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = [
        'supplier_id',
        'collection_date',
        'residue_type',
        'weight',
        'price_per_kg',
        'total_cost',
        'driver_name',
        'vehicle_plate',
        'status',
        'batch_id'
    ];

    protected $casts = [
        'collection_date' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
