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
        'price_per_kg'
    ];

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
}
