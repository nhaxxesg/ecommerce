<?php

namespace App\Models\Schemas;

/**
 * @OA\Schema(
 *     schema="Menu",
 *     required={"name", "description", "price", "restaurant_id", "available_date", "food_type"},
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="name", type="string", description="Nombre del plato"),
 *     @OA\Property(property="description", type="string", description="Descripción del plato"),
 *     @OA\Property(property="price", type="number", format="float", description="Precio del plato"),
 *     @OA\Property(property="image_url", type="string", nullable=true, description="URL de la imagen"),
 *     @OA\Property(property="restaurant_id", type="integer", description="ID del restaurante"),
 *     @OA\Property(property="available_date", type="string", format="date", description="Fecha de disponibilidad"),
 *     @OA\Property(property="food_type", type="string", description="Tipo de comida"),
 *     @OA\Property(property="is_available", type="boolean", description="Disponibilidad del plato")
 * )
 */
class MenuSchema {}
