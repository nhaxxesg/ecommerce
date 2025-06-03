<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/orders",
     *     summary="Obtener lista de pedidos",
     *     tags={"Pedidos"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pedidos según el rol del usuario"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::find($request->user_id);
        
        // Si es propietario, mostrar pedidos de sus restaurantes
        if ($user->role === 'propietario') {
            $orders = Order::whereHas('restaurant', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with(['items.menu', 'customer'])->get();
        } else {
            // Si es cliente, mostrar sus pedidos
            $orders = Order::where('user_id', $user->id)
                         ->with(['items.menu', 'restaurant'])
                         ->get();
        }
        
        return response()->json($orders);
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     summary="Crear un nuevo pedido",
     *     tags={"Pedidos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "restaurant_id", "items"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="restaurant_id", type="integer", example=1),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="menu_id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pedido creado exitosamente"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = Order::create([
            'user_id' => $validated['user_id'],
            'restaurant_id' => $validated['restaurant_id'],
            'status' => 'pendiente',
            'total_amount' => 0,
            'order_number' => Str::random(10)
        ]);

        foreach ($validated['items'] as $item) {
            $order->items()->create([
                'menu_id' => $item['menu_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return response()->json(['order' => $order->load('items')], 201);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // Verificar si el usuario tiene acceso al pedido
        if (!$this->userCanAccessOrder($order, $request->user_id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($order->load(['items.menu', 'restaurant']));
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::find($request->user_id);
        
        // Solo el propietario del restaurante puede actualizar el estado
        if ($order->restaurant->user_id !== $user->id || $user->role !== 'propietario') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pendiente,en preparación,listo para entrega,entregado'
        ]);

        $order->update($validated);
        
        return response()->json($order);
    }

    /**
     * Completar el pago de una orden
     * 
     * @OA\Post(
     *     path="/api/orders/completar-pago",
     *     summary="Completar pago de una orden",
     *     tags={"Pedidos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "restaurant_id", "items", "amount", "provider", "provider_payment_id", "status"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="restaurant_id", type="integer", example=1),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="menu_id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             ),
     *             @OA\Property(property="amount", type="number", format="float", example=29.99),
     *             @OA\Property(property="provider", type="string", example="stripe"),
     *             @OA\Property(property="provider_payment_id", type="string", example="pi_1GqICL2eZvKYlo2Cj8z5Kj6A"),
     *             @OA\Property(property="status", type="string", enum={"completed", "cancelled", "failed"}, example="completed"),
     *             @OA\Property(property="raw_response", type="array", @OA\Items(), example={"key": "value"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pago completado y orden creada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en los datos de entrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function completarPago(Request $request)
    {
        //Log::info('completarPago ejecutado', $request->all());
        // o
        // dd('completarPago ejecutado', $request->all());
        // ...resto del código...

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'amount' => 'required|numeric',
            'provider' => 'required|string',
            'provider_payment_id' => 'required|string',
            'status' => 'required|in:completed,cancelled,failed',
            'raw_response' => 'nullable|array',
        ]);

        if ($request->status !== 'completed') {
            return response()->json(['error' => 'El pago no fue completado'], 400);
        }

        DB::beginTransaction();
        try {
            // 1. Crear la orden
            $order = Order::create([
                'user_id' => $request->user_id,
                'restaurant_id' => $request->restaurant_id,
                'status' => 'pendiente',
                'total_amount' => $request->amount,
                'order_number' => \Illuminate\Support\Str::random(10),
            ]);

            // 2. Crear los items de la orden
            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $menu->price,
                    'subtotal' => $menu->price * $item['quantity'],
                ]);
            }

            // 3. Registrar el pago
            Payment::create([
                'order_id' => $order->id,
                'amount' => $request->amount,
                'provider' => $request->provider,
                'provider_payment_id' => $request->provider_payment_id,
                'status' => $request->status,
                'raw_response' => $request->raw_response,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'order' => $order->load('items.menu', 'payment')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar la orden o el pago', 'details' => $e->getMessage()], 500);
        }
    }

    private function userCanAccessOrder(Order $order, int $userId): bool
    {
        $user = User::find($userId);
        return $userId === $order->user_id || 
               ($user->role === 'propietario' && $userId === $order->restaurant->user_id);
    }
}
