<?php

namespace App\Virtual;

/**
 * @OA\Schema(
 *     title="Restaurant",
 *     description="Modelo de Restaurante",
 *     @OA\Xml(name="Restaurant")
 * )
 */
class Restaurant
{
    /**
     * @OA\Property(type="integer", format="int64", example=1)
     */
    private $id;

    /**
     * @OA\Property(type="string", example="Restaurante Demo")
     */
    private $name;

    /**
     * @OA\Property(type="string", example="Calle Principal #123")
     */
    private $address;

    /**
     * @OA\Property(type="string", example="Lun-Vie: 9:00-21:00")
     */
    private $schedule;

    /**
     * @OA\Property(type="string", example="555-0123")
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
