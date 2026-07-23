<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealQualityCertificate extends Model
{
    protected $table = 'meal_quality_certificates';

    protected $fillable = [
        'sale_id',
        'client_id',
        'analysis_date',
        'revisao_number',
        'invoice_number',
        'weight',
        'vehicle_plate',
        'driver_name',
        'driver_cpf',
        'seal_number',
        'non_conformities',
        'corrective_actions',
        'verification',
    ];

    protected $casts = [
        'analysis_date' => 'date',
        'revisao_number' => 'integer',
        'weight' => 'decimal:2',
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
