<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use DB;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        // Si es propietario, mostrar pedidos de sus restaurantes
        if (auth()->user()->isOwner()) {
            $orders = Order::whereHas('restaurant', function($query) {
                $query->where('user_id', auth()->id());
            })->with(['items.menu', 'customer'])->get();
        } else {
            // Si es cliente, mostrar sus pedidos
            $orders = Order::where('user_id', auth()->id())
                         ->with(['items.menu', 'restaurant'])
                         ->get();
        }
        
        return response()->json($orders);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            return DB::transaction(function() use ($validated) {
                // Crear el pedido
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'restaurant_id' => $validated['restaurant_id'],
                    'status' => 'pendiente',
                    'total_amount' => 0,
                    'order_number' => Str::random(10)
                ]);

                $total = 0;

                // Crear los items del pedido
                foreach ($validated['items'] as $item) {
                    $menu = Menu::findOrFail($item['menu_id']);
                    $subtotal = $menu->price * $item['quantity'];
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $item['menu_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $menu->price,
                        'subtotal' => $subtotal
                    ]);

                    $total += $subtotal;
                }

                // Actualizar el total del pedido
                $order->update(['total_amount' => $total]);

                return response()->json($order->load('items.menu'), 201);
            });
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar el pedido'], 500);
        }
    }

    public function show(Order $order): JsonResponse
    {
        // Verificar si el usuario tiene acceso al pedido
        if (!$this->userCanAccessOrder($order)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($order->load(['items.menu', 'restaurant']));
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        // Solo el propietario del restaurante puede actualizar el estado
        if ($order->restaurant->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pendiente,en preparación,listo para entrega,entregado'
        ]);

        $order->update($validated);
        
        return response()->json($order);
    }

    private function userCanAccessOrder(Order $order): bool
    {
        return auth()->id() === $order->user_id || 
               auth()->id() === $order->restaurant->user_id;
    }
}
