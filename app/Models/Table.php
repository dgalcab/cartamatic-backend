<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    // Campos que se pueden asignar de forma masiva
    protected $fillable = ['restaurant_id', 'unique_number', 'capacity', 'location']; // Añadir 'location'

    // Relación con el restaurante
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Relación con las reservas
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
