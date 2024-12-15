<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = ['user_id', 'restaurant_id', 'menu_item_id'];

    // Relación: Un favorito pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: Un favorito puede estar relacionado con un restaurante
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Relación: Un favorito puede estar relacionado con un plato
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
