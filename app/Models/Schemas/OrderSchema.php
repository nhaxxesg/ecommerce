<?php

namespace App\Models\Schemas;

/**
 * @OA\Schema(
 *     schema="Order",
 *     required={"restaurant_id", "items"},
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="user_id", type="integer", description="ID del cliente"),
 *     @OA\Property(property="restaurant_id", type="integer", description="ID del restaurante"),
 *     @OA\Property(property="total_amount", type="number", format="float", readOnly=true),
 *     @OA\Property(property="status", type="string", enum={"pendiente", "en preparación", "listo para entrega", "entregado"}),
 *     @OA\Property(property="order_number", type="string", readOnly=true),
 *     @OA\Property(
 *         property="items",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/OrderItem")
 *     )
 * )
 */
class OrderSchema {}

/**
 * @OA\Schema(
 *     schema="OrderItem",
 *     required={"menu_id", "quantity"},
 *     @OA\Property(property="menu_id", type="integer", description="ID del menú"),
 *     @OA\Property(property="quantity", type="integer", description="Cantidad ordenada"),
 *     @OA\Property(property="unit_price", type="number", format="float", readOnly=true),
 *     @OA\Property(property="subtotal", type="number", format="float", readOnly=true)
 * )
 */
class OrderItemSchema {}
