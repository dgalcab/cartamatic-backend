<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantSchedule extends Model
{
    protected $fillable = ['restaurant_id', 'day_of_week', 'open_time', 'close_time'];

    // Relación: Un horario pertenece a un restaurante
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
