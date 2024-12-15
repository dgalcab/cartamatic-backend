<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allergen extends Model
{
    protected $fillable = ['name', 'description', 'icon_url'];

    // Relación: Un alérgeno puede estar presente en varios platos
    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_allergens');
    }
}
