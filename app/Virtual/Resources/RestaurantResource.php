<?php

namespace App\Virtual\Resources;

/**
 * @OA\Schema(
 *     title="Restaurant",
 *     description="Modelo de Restaurante",
 *     @OA\Xml(name="Restaurant")
 * )
 */
class RestaurantResource
{
    /**
     * @OA\Property(type="integer", format="int64", example=1)
     */
    private $id;

    /**
     * @OA\Property(type="string", example="Mi Restaurante")
     */
    private $name;

    /**
     * @OA\Property(type="string", example="Calle Principal #123")
     */
    private $address;

    /**
     * @OA\Property(type="string", example="Lun-Vie: 9am-9pm")
     */
    private $schedule;

    /**
     * @OA\Property(type="string", example="telefono: 555-1234")
     */
    private $contact_info;

    /**
     * @OA\Property(type="number", format="float", example=19.4326)
     */
    private $latitude;

    /**
     * @OA\Property(type="number", format="float", example=-99.1332)
     */
    private $longitude;
}
