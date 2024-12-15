<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
        'vegan',
        'spicy',
        'category_id',  // Usar 'category_id' en lugar de 'menu_id'
        'restaurant_id' // Asegúrate de que 'restaurant_id' también esté configurado
    ];

    // Relación: Un plato pertenece a una categoría de menú
    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }

    // Relación de muchos a muchos con los alérgenos
    public function allergens()
    {
        return $this->belongsToMany(Allergen::class, 'menu_item_allergens');
    }
}
