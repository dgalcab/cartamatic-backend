<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'user_id', 'name', 'description', 'location', 'phone', 'email', 'website_url', 'logo_url', 'image_url'
    ];

    // Relación: Un restaurante pertenece a un usuario (propietario)
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación: Un restaurante puede tener varias categorías de menús
    public function menuCategories()
    {
        return $this->hasMany(MenuCategory::class);
    }

    // Relación: Un restaurante puede tener varios platos
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    // Relación: Un restaurante puede tener varias reservas
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Relación: Un restaurante puede tener varios favoritos
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Relación: Un restaurante tiene varios horarios
    public function schedules()
    {
        return $this->hasMany(RestaurantSchedule::class);
    }

    // Relación: Un restaurante puede tener varias mesas
    public function tables()
    {
        return $this->hasMany(Table::class);
    }
}

