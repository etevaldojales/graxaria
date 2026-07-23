<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteCommissionParameter extends Model
{
    protected $table = 'route_commission_parameters';

    protected $fillable = [
        'route_id',
        'residue_id',
        'commission_per_kg_driver',
        'commission_per_kg_helper',
    ];

    protected $casts = [
        'commission_per_kg_driver' => 'decimal:4',
        'commission_per_kg_helper' => 'decimal:4',
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function residue(): BelongsTo
    {
        return $this->belongsTo(Residue::class);
    }
}
