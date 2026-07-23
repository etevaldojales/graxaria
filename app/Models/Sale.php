<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'client_id',
        'product_type',
        'weight',
        'price_per_kg',
        'total_value',
        'sale_date',
        'status'
    ];

    protected $casts = [
        'sale_date' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function tallowCertificates()
    {
        return $this->hasMany(TallowQualityCertificate::class);
    }

    public function mealCertificates()
    {
        return $this->hasMany(MealQualityCertificate::class);
    }
}
