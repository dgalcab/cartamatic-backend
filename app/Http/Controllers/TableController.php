<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TableController extends Controller
{
    // Mostrar las mesas de un restaurante específico
    public function index($restaurantId)
    {
        // Verificar si el usuario autenticado es el propietario del restaurante
        $restaurant = Restaurant::findOrFail($restaurantId);

        if (Auth::id() != $restaurant->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Obtener todas las mesas del restaurante
        $tables = Table::where('restaurant_id', $restaurantId)->get();

        return response()->json($tables);
    }

    // Crear una nueva mesa en el restaurante
    public function store(Request $request, $restaurantId)
    {
        // Validar los datos de la solicitud
        $request->validate([
            'unique_number' => 'required|string|unique:tables',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string', // Permitir ubicación opcional
        ]);

        // Verificar si el usuario autenticado es el propietario del restaurante
        $restaurant = Restaurant::findOrFail($restaurantId);

        if (Auth::id() != $restaurant->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Crear la mesa
        $table = Table::create([
            'restaurant_id' => $restaurantId,
            'unique_number' => $request->unique_number,
            'capacity' => $request->capacity,
            'location' => $request->location, // Guardar la ubicación si se proporciona
        ]);

        return response()->json(['message' => 'Mesa creada exitosamente', 'table' => $table], 201);
    }

    // Mostrar una mesa específica (por ID)
    public function show($restaurantId, $tableId)
    {
        // Verificar si el usuario autenticado es el propietario del restaurante
        $restaurant = Restaurant::findOrFail($restaurantId);

        if (Auth::id() != $restaurant->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Obtener la mesa
        $table = Table::where('restaurant_id', $restaurantId)
            ->where('id', $tableId)
            ->firstOrFail();

        return response()->json($table);
    }

    // Actualizar los datos de una mesa específica
    public function update(Request $request, $restaurantId, $tableId)
    {
        // Validar los datos
        $request->validate([
            'unique_number' => 'required|string|unique:tables,unique_number,' . $tableId,
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string',
        ]);

        // Verificar si el usuario autenticado es el propietario del restaurante
        $restaurant = Restaurant::findOrFail($restaurantId);

        if (Auth::id() != $restaurant->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Obtener la mesa
        $table = Table::where('restaurant_id', $restaurantId)
            ->where('id', $tableId)
            ->firstOrFail();

        // Actualizar los datos de la mesa
        $table->update([
            'unique_number' => $request->unique_number,
            'capacity' => $request->capacity,
            'location' => $request->location,
        ]);

        return response()->json(['message' => 'Mesa actualizada exitosamente', 'table' => $table], 200);
    }

    // Eliminar una mesa
    public function destroy($restaurantId, $tableId)
    {
        // Verificar si el usuario autenticado es el propietario del restaurante
        $restaurant = Restaurant::findOrFail($restaurantId);

        if (Auth::id() != $restaurant->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Eliminar la mesa
        $table = Table::where('restaurant_id', $restaurantId)
            ->where('id', $tableId)
            ->firstOrFail();

        $table->delete();

        return response()->json(['message' => 'Mesa eliminada exitosamente'], 200);
    }
}
