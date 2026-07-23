<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TallowQualityCertificate extends Model
{
    protected $table = 'tallow_quality_certificates';

    protected $fillable = [
        'sale_id',
        'client_id',
        'analysis_date',
        'shipping_date',
        'production_date',
        'expiry_info',
        'result_aspect',
        'result_acidity',
        'result_impurities',
        'result_odor',
        'result_moisture',
        'vehicle_plate',
        'carrier_name',
        'invoice_number',
        'seal_number',
        'inspected_clean_external',
        'inspected_clean_internal',
        'inspected_dry_internal',
        'is_released',
        'qa_responsible',
        'technical_responsible',
    ];

    protected $casts = [
        'analysis_date' => 'date',
        'shipping_date' => 'date',
        'inspected_clean_external' => 'boolean',
        'inspected_clean_internal' => 'boolean',
        'inspected_dry_internal' => 'boolean',
        'is_released' => 'boolean',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
