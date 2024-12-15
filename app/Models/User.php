<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;  // HasApiTokens para usar Sanctum

    protected $fillable = ['name', 'email', 'password', 'role'];

    // Relación: Un usuario puede tener varios restaurantes (si es propietario)
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    // Relación: Un usuario puede tener varios favoritos
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Relación: Un usuario puede hacer varias reservas
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Relación: Un usuario puede dejar varias reseñas de platos
    public function dishReviews()
    {
        return $this->hasMany(DishReview::class);
    }
}
