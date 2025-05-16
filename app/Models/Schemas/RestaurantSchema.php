<?php

namespace App\Models\Schemas;

/**
 * @OA\Schema(
 *     schema="Restaurant",
 *     required={"name", "address", "schedule", "contact_info", "latitude", "longitude"},
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="name", type="string", description="Nombre del restaurante"),
 *     @OA\Property(property="address", type="string", description="Dirección del restaurante"),
 *     @OA\Property(property="schedule", type="string", description="Horario de atención"),
 *     @OA\Property(property="contact_info", type="string", description="Información de contacto"),
 *     @OA\Property(property="latitude", type="number", format="float", description="Latitud para el mapa"),
 *     @OA\Property(property="longitude", type="number", format="float", description="Longitud para el mapa"),
 *     @OA\Property(property="user_id", type="integer", description="ID del propietario")
 * )
 */
class RestaurantSchema {}
