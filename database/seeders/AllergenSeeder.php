<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllergenSeeder extends Seeder
{
    public function run()
    {
        DB::table('allergens')->insert([
            ['name' => 'gluten', 'icon_url' => 'restaurants/allergens/gluten.png'],
            ['name' => 'crustaceos', 'icon_url' => 'restaurants/allergens/crustaceos.png'],
            ['name' => 'huevos', 'icon_url' => 'restaurants/allergens/huevos.png'],
            ['name' => 'pescado', 'icon_url' => 'restaurants/allergens/pescado.png'],
            ['name' => 'cacahuetes', 'icon_url' => 'restaurants/allergens/cacahuetes.png'],
            ['name' => 'soja', 'icon_url' => 'restaurants/allergens/soja.png'],
            ['name' => 'lacteos', 'icon_url' => 'restaurants/allergens/lacteos.png'],
            ['name' => 'frutos-secos', 'icon_url' => 'restaurants/allergens/frutos-secos.png'],
            ['name' => 'apio', 'icon_url' => 'restaurants/allergens/apio.png'],
            ['name' => 'mostaza', 'icon_url' => 'restaurants/allergens/mostaza.png'],
            ['name' => 'sesamo', 'icon_url' => 'restaurants/allergens/sesamo.png'],
            ['name' => 'sulfitos', 'icon_url' => 'restaurants/allergens/sulfitos.png'],
            ['name' => 'altramuz', 'icon_url' => 'restaurants/allergens/altramuz.png'],
            ['name' => 'moluscos', 'icon_url' => 'restaurants/allergens/moluscos.png'],
        ]);
    }
}
