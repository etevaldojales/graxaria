<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierProductPrice extends Model
{
    protected $table = 'supplier_product_prices';

    protected $fillable = [
        'supplier_id',
        'residue_id',
        'price_per_kg',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function residue()
    {
        return $this->belongsTo(Residue::class);
    }
}
