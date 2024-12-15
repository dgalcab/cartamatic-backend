<?php
namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    // Listar todos los platos dentro de una categoría de menú
    public function index($categoryId)
    {
        return MenuItem::where('category_id', $categoryId)
            ->with('allergens')
            ->get();
    }

    // Crear un nuevo plato en una categoría de menú específica
    public function store(Request $request, $categoryId)
    {
        try {
            // Validar los datos recibidos, incluyendo alérgenos si se pasan
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'availability' => 'sometimes|boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validar imagen
                'allergens' => 'nullable|array', // Asegurarse de que allergens sea un array
                'allergens.*' => 'integer|exists:allergens,id' // Validar que cada alérgeno sea un entero y exista en la tabla allergens
            ]);

            // Obtener la categoría de menú
            $menuCategory = MenuCategory::findOrFail($categoryId);
            $data['category_id'] = $categoryId;
            $data['restaurant_id'] = $menuCategory->restaurant_id;

            // Si se subió una imagen, guardarla en el sistema de archivos
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('menu-items', 'public');
                $data['image_url'] = Storage::url($path); // Guardar URL de la imagen
            }

            // Crear el nuevo plato
            $menuItem = MenuItem::create($data);

            // Sincronizar los alérgenos en la tabla pivot si se seleccionaron
            if ($request->has('allergens') && is_array($request->input('allergens'))) {
                $menuItem->allergens()->sync($request->input('allergens'));
            }

            return response()->json($menuItem, 201);
        } catch (\Exception $e) {
            \Log::error('Error creando el plato: ' . $e->getMessage());
            return response()->json(['error' => 'Error en el servidor.'], 500);
        }
    }

    // Mostrar un plato específico dentro de una categoría de menú
    public function show($restaurantId, $categoryId, $menuItemId)
    {
        try {
            // Verificar que la categoría pertenece al restaurante
            $menuCategory = MenuCategory::where('id', $categoryId)
                ->where('restaurant_id', $restaurantId)
                ->firstOrFail();

            // Obtener el plato asegurándose de que pertenece a la categoría y al restaurante
            $menuItem = MenuItem::where('id', $menuItemId)
                ->where('category_id', $categoryId)
                ->where('restaurant_id', $restaurantId)
                ->with('allergens')  // Incluir los alérgenos asociados
                ->firstOrFail();

            // Devolver el plato con un código 200 si todo está bien
            return response()->json($menuItem, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Error cuando no se encuentra la categoría o el plato
            return response()->json(['error' => 'Categoría o plato no encontrado.'], 404);
        } catch (\Exception $e) {
            // Manejo general de errores de servidor
            \Log::error('Error obteniendo el plato: ' . $e->getMessage());
            return response()->json(['error' => 'Error en el servidor.'], 500);
        }
    }
    // Actualizar un plato específico dentro de una categoría de menú
    public function update(Request $request, $restaurantId, $categoryId, $menuItemId)
    {
        try {
            // Validar los datos de la solicitud
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'category_id' => 'required|integer',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validar imagen
                'allergens' => 'array', // Validar que los alérgenos sean un array
            ]);

            // Buscar el plato asegurando que pertenece tanto al restaurante como a la categoría
            $menuItem = MenuItem::where('id', $menuItemId)
                ->where('restaurant_id', $restaurantId)
                ->where('category_id', $categoryId)
                ->firstOrFail();

            // Si se subió una nueva imagen, eliminar la anterior y guardar la nueva
            if ($request->hasFile('image')) {
                if ($menuItem->image_url) {
                    $previousImagePath = str_replace('/storage/', '', $menuItem->image_url);
                    Storage::disk('public')->delete($previousImagePath);
                }

                // Guardar la nueva imagen
                $path = $request->file('image')->store('menu-items', 'public');
                $data['image_url'] = Storage::url($path);
            }

            // Actualizar los datos del plato
            $menuItem->update($data);

            // Sincronizar los alérgenos si existen en la solicitud
            if ($request->has('allergens')) {
                $menuItem->allergens()->sync($request->input('allergens'));
            }

            return response()->json($menuItem, 200);
        } catch (\Exception $e) {
            \Log::error('Error actualizando el plato: ' . $e->getMessage());
            return response()->json(['error' => 'Error actualizando el plato.'], 500);
        }
    }

    // Eliminar un plato específico dentro de una categoría de menú
    public function destroy($restaurantId, $categoryId, $menuItemId)
    {
        try {
            // Verificar que el item pertenece a la categoría y al restaurante
            $menuItem = MenuItem::where('category_id', $categoryId)
                ->where('restaurant_id', $restaurantId)
                ->findOrFail($menuItemId);

            // Eliminar la imagen asociada si existe
            if ($menuItem->image_url) {
                $imagePath = str_replace('/storage/', '', $menuItem->image_url);
                Storage::disk('public')->delete($imagePath);
            }

            // Eliminar el plato
            $menuItem->delete();

            return response()->json(['message' => 'Plato eliminado con éxito.'], 200);
        } catch (\Exception $e) {
            \Log::error('Error eliminando el plato: ' . $e->getMessage());
            return response()->json(['error' => 'Error en el servidor.'], 500);
        }
    }
}
