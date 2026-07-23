<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperationalCost extends Model
{
    protected $table = 'operational_costs';

    protected $fillable = [
        'vehicle_id',
        'driver_user_id',
        'cost_category_id',
        'description',
        'value',
        'invoice_number',
        'cost_date',
        'inventory_item_id',
        'quantity',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'cost_date' => 'date',
        'quantity' => 'decimal:2',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CostCategory::class, 'cost_category_id');
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    protected static function booted()
    {
        static::created(function ($cost) {
            if ($cost->inventory_item_id && $cost->quantity > 0) {
                InventoryTransaction::create([
                    'inventory_item_id' => $cost->inventory_item_id,
                    'type' => 'Saída',
                    'quantity' => $cost->quantity,
                    'description' => "Manutenção do veículo " . ($cost->vehicle?->plate ?? '') . " (Despesa #{$cost->id})",
                    'transaction_date' => $cost->cost_date ?? now(),
                ]);
            }
        });
    }
}
