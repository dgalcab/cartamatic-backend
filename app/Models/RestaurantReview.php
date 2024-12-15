<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantReview extends Model
{
    protected $fillable = ['user_id', 'restaurant_id', 'rating', 'comment'];

    // Relación: Una reseña de restaurante pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: Una reseña pertenece a un restaurante
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
