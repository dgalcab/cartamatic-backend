<?php

namespace App\Http\Controllers;

use App\Models\Allergen;
use Illuminate\Http\Request;

class AllergenController extends Controller
{

    public function allergens()
    {
        // Obtener todos los alérgenos desde la base de datos
        return response()->json(Allergen::all());
    }
}
