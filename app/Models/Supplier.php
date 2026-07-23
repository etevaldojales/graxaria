<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'document',
        'type',
        'phone',
        'email',
        'address',
        'price_per_kg',
        'route_id',
    ];

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function productPrices()
    {
        return $this->hasMany(SupplierProductPrice::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
