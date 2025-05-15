<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'address',
        'schedule',
        'contact_info',
        'latitude',
        'longitude',
        'user_id'
    ];

    // Relación con el propietario (usuario)
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con los menús
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }
}
