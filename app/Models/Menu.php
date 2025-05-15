<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
        'restaurant_id',
        'available_date', // Para menús diarios
        'food_type',      // Para filtrado por tipo de comida
        'is_available'    // Para control de disponibilidad
    ];

    // Relación con el restaurante
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
