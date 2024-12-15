<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    public function index($restaurantId)
    {
        $categories = MenuCategory::where('restaurant_id', $restaurantId)
            ->with(['items.allergens']) // Cargar también la relación de alérgenos para cada ítem
            ->get();

        return response()->json($categories);
    }

    // Crear una nueva categoría en un restaurante específico
    public function store(Request $request)
    {
        // Validamos los datos, incluyendo restaurant_id en el cuerpo de la solicitud
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'restaurant_id' => 'required|exists:restaurants,id', // Validamos que el ID del restaurante exista
        ]);

        // Creamos la nueva categoría con los datos proporcionados
        return MenuCategory::create($data);
    }


    // Mostrar una categoría específica dentro de un restaurante
    public function show($restaurantId, $menuCategoryId)
    {
        // Asegurarse de que la categoría pertenece al restaurante
        return MenuCategory::where('restaurant_id', $restaurantId)
            ->findOrFail($menuCategoryId);
    }

    // Actualizar una categoría de menú específica de un restaurante
    public function update(Request $request, $restaurantId, $menuCategoryId)
    {
        $category = MenuCategory::where('restaurant_id', $restaurantId)
            ->findOrFail($menuCategoryId);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($data);
        return $category;
    }

    // Eliminar una categoría de menú específica dentro de un restaurante
    public function destroy($restaurantId, $menuCategoryId)
    {
        $category = MenuCategory::where('restaurant_id', $restaurantId)
            ->findOrFail($menuCategoryId);

        $category->delete();
        return response()->json(['message' => 'Categoría borrada satisfactoriamente.']);
    }
}
