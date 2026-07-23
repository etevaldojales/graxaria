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
        'batch_id',
        'driver_user_id',
        'vehicle_id',
        'helper_id',
        'helper_2_id',
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

    public function items()
    {
        return $this->hasMany(CollectionItem::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_user_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function helper()
    {
        return $this->belongsTo(Helper::class, 'helper_id');
    }

    public function helper2()
    {
        return $this->belongsTo(Helper::class, 'helper_2_id');
    }

    public function getWeightAttribute()
    {
        if ($this->items()->exists()) {
            return $this->items()->sum('weight');
        }
        return $this->attributes['weight'] ?? 0.00;
    }

    public function getTotalCostAttribute()
    {
        if ($this->items()->exists()) {
            return $this->items()->sum('total_cost');
        }
        return $this->attributes['total_cost'] ?? 0.00;
    }

    public function getResidueTypeAttribute()
    {
        if ($this->items()->exists()) {
            return $this->items->map(fn ($item) => $item->residue?->name)->filter()->implode(', ');
        }
        return $this->attributes['residue_type'] ?? 'Misto';
    }
}
