<?php

/**
 * @OA\Schema(
 *     schema="Menu",
 *     title="Menu",
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
 */

/**
 * @OA\Schema(
 *     schema="Restaurant",
 *     title="Restaurant",
 *     description="Modelo de Restaurante",
 *     required={"name", "address", "schedule", "contact_info", "latitude", "longitude"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="Restaurante Demo"),
 *     @OA\Property(property="address", type="string", example="Calle Principal #123"),
 *     @OA\Property(property="schedule", type="string", example="Lun-Vie: 9:00-21:00"),
 *     @OA\Property(property="contact_info", type="string", example="555-0123"),
 *     @OA\Property(property="latitude", type="number", format="float", example=19.4326),
 *     @OA\Property(property="longitude", type="number", format="float", example=-99.1332),
 *     @OA\Property(property="user_id", type="integer", example=1)
 * )
 */

/**
 * @OA\Schema(
 *     schema="Order",
 *     title="Order",
 *     description="Modelo de Pedido",
 *     required={"restaurant_id", "items"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="restaurant_id", type="integer", example=1),
 *     @OA\Property(property="total_amount", type="number", format="float", example=29.99),
 *     @OA\Property(property="status", type="string", enum={"pendiente", "en preparación", "listo para entrega", "entregado"}),
 *     @OA\Property(property="order_number", type="string", example="ORD123456"),
 *     @OA\Property(
 *         property="items",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/OrderItem")
 *     )
 * )
 */

/**
 * @OA\Schema(
 *     schema="OrderItem",
 *     title="OrderItem",
 *     description="Item del pedido",
 *     required={"menu_id", "quantity"},
 *     @OA\Property(property="menu_id", type="integer", example=1),
 *     @OA\Property(property="quantity", type="integer", example=2),
 *     @OA\Property(property="unit_price", type="number", format="float", example=9.99),
 *     @OA\Property(property="subtotal", type="number", format="float", example=19.98)
 * )
 */
