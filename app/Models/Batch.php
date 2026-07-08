<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [
        'batch_code',
        'start_date',
        'end_date',
        'input_weight',
        'output_tallow_weight',
        'output_meal_weight',
        'yield_percentage',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
}
