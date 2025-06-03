<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'total_amount',
        'status', // pendiente, en preparación, listo para entrega, entregado
        'order_number'
    ];

    // Relación con el cliente
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el restaurante
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Relación con los items del pedido
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
