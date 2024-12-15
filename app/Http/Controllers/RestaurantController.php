<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Http\Requests\RestaurantRequest;  // Importar el Form Request para la validación
use App\Services\FileUploadService;  // Importar el servicio de subida de archivos
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    // Mostrar todos los restaurantes (Rutas públicas)
    public function index()
    {
        return Restaurant::all();
    }

    // Mostrar un restaurante específico (Rutas públicas)
    public function show($id)
    {
        try {
            $restaurant = Restaurant::findOrFail($id);  // Manejar la excepción si no se encuentra el modelo
            return response()->json($restaurant, 200);  // Devolver el restaurante encontrado
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Restaurante no encontrado'], 404);
        }
    }

    // Almacenar un nuevo restaurante (Requiere autenticación y autorización)
    public function store(RestaurantRequest $request)  // Usamos el Form Request para la validación
    {
        $validated = $request->validated();  // Los datos ya han sido validados

        // Subir el logo si está presente
        if ($request->hasFile('logo')) {
            $validated['logo_url'] = FileUploadService::upload($request->file('logo'), 'restaurants/logos');
        }

        // Subir la imagen principal si está presente
        if ($request->hasFile('image')) {
            $validated['image_url'] = FileUploadService::upload($request->file('image'), 'restaurants/images');
        }

        // Asignar el user_id del usuario autenticado
        $validated['user_id'] = auth()->id();

        // Crear el restaurante con los datos validados
        $restaurant = Restaurant::create($validated);

        return response()->json([
            'message' => 'Restaurante creado con éxito',
            'id' => $restaurant->id,
            'restaurant' => $restaurant
        ], 201);  // Devolver el restaurante creado
    }

    // Actualizar un restaurante existente (Requiere autenticación y autorización)
    public function update(RestaurantRequest $request, $id)
    {
        try {
            $restaurant = Restaurant::findOrFail($id);  // Si no se encuentra el restaurante, lanza una excepción

            // Usamos la policy para verificar la autorización del usuario
            $this->authorize('update', $restaurant);

            $data = $request->validated();  // Validar los datos enviados

            // Subir un nuevo logo si está presente
            if ($request->hasFile('logo')) {
                // Borrar el antiguo logo si existe
                FileUploadService::delete($restaurant->logo_url);
                $data['logo_url'] = FileUploadService::upload($request->file('logo'), 'restaurants/logos');
            }

            // Subir una nueva imagen principal si está presente
            if ($request->hasFile('image')) {
                // Borrar la antigua imagen si existe
                FileUploadService::delete($restaurant->image_url);
                $data['image_url'] = FileUploadService::upload($request->file('image'), 'restaurants/images');
            }

            // Actualizar los datos del restaurante
            $restaurant->update($data);

            return response()->json([
                'message' => 'Restaurante actualizado con éxito',
                'restaurant' => $restaurant
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Restaurante no encontrado'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No autorizado para actualizar este restaurante'], 403);
        }
    }

    // Eliminar un restaurante existente (Requiere autenticación y autorización)
    public function destroy($id)
    {
        try {
            $restaurant = Restaurant::findOrFail($id);  // Si no se encuentra el restaurante, lanza una excepción

            // Usamos la policy para verificar la autorización del usuario
            $this->authorize('delete', $restaurant);

            // Borrar el logo y la imagen principal si existen
            FileUploadService::delete($restaurant->logo_url);
            FileUploadService::delete($restaurant->image_url);

            // Eliminar el restaurante
            $restaurant->delete();

            return response()->json(['message' => 'Restaurante eliminado con éxito'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Restaurante no encontrado'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'No autorizado para eliminar este restaurante'], 403);
        }
    }
}
