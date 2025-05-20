<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @OA\Schema(
 *     schema="Menu",
 *     description="Modelo de Menú",
 *     required={"name", "description", "price", "restaurant_id", "food_type"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="Hamburguesa Clásica"),
 *     @OA\Property(property="description", type="string", example="Deliciosa hamburguesa con queso y vegetales frescos"),
 *     @OA\Property(property="price", type="number", format="float", example=9.99),
 *     @OA\Property(property="image_url", type="string", example="https://example.com/images/burger.jpg"),
 *     @OA\Property(property="restaurant_id", type="integer", example=1),
 *     @OA\Property(property="available_date", type="string", format="date", example="2025-05-16"),
 *     @OA\Property(property="food_type", type="string", example="Hamburguesas"),
 *     @OA\Property(property="is_available", type="boolean", example=true)
 * )
**/
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
