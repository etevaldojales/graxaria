<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionItem extends Model
{
    protected $table = 'collection_items';

    protected $fillable = [
        'collection_id',
        'residue_id',
        'weight',
        'price_per_kg',
        'total_cost',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function residue()
    {
        return $this->belongsTo(Residue::class);
    }
}
