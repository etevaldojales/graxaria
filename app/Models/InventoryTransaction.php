<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    protected $table = 'inventory_transactions';

    protected $fillable = [
        'inventory_item_id',
        'type',
        'quantity',
        'description',
        'transaction_date',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    protected static function booted()
    {
        static::created(function ($transaction) {
            $item = $transaction->item;
            if ($item) {
                if ($transaction->type === 'Entrada') {
                    $item->increment('stock', $transaction->quantity);
                } elseif ($transaction->type === 'Saída') {
                    $item->decrement('stock', $transaction->quantity);
                }
            }
        });
    }
}
