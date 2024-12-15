<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DishReview extends Model
{
    protected $fillable = ['user_id', 'menu_item_id', 'rating', 'comment'];

    // Relación: Una reseña de plato pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: Una reseña de plato pertenece a un plato
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
