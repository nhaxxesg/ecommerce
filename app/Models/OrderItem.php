<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'unit_price',
        'subtotal'
    ];

    // Relación con el pedido
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Relación con el menú
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
