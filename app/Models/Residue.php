<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Residue extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function supplierPrices()
    {
        return $this->hasMany(SupplierProductPrice::class);
    }

    public function collectionItems()
    {
        return $this->hasMany(CollectionItem::class);
    }
}
