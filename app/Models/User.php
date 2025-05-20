<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * @OA\Schema(
     *     schema="User",
     *     required={"name", "email", "password", "role"},
     *     @OA\Property(property="id", type="integer", format="int64", readOnly=true),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="email", type="string", format="email"),
     *     @OA\Property(property="role", type="string", enum={"cliente", "propietario"}),
     *     @OA\Property(property="created_at", type="string", format="datetime", readOnly=true),
     *     @OA\Property(property="updated_at", type="string", format="datetime", readOnly=true)
     * )
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Verifica si el usuario es un propietario de restaurante
     */
    public function isOwner(): bool
    {
        return $this->role === 'propietario';
    }

    /**
     * Verifica si el usuario es un cliente
     */
    public function isCustomer(): bool
    {
        return $this->role === 'cliente';
    }
}
