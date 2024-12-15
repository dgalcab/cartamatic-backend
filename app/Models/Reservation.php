<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'restaurant_id', 'table_id', 'datetime', 'num_people', 'status',
        'client_name', 'client_email', 'client_phone'
    ];

    // Relación con el restaurante
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Relación con la mesa
    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
