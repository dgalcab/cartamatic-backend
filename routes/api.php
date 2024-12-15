<?php

use Illuminate\Http\Request;
use App\Events\TestPusherEvent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\AllergenController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\DishReviewController;
use App\Http\Controllers\RestaurantScheduleController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\AuthController;

// Rutas públicas (no requieren autenticación)

Route::post('/test-pusher', function (Request $request) {
    event(new TestPusherEvent($request->message));
    return response()->json(['message' => 'Evento de Pusher enviado correctamente.']);
});


Route::post('/register', [AuthController::class, 'register']);  // Registro de usuarios
Route::post('/login', [AuthController::class, 'login']);  // Inicio de sesión
Route::get('/allergens', [AllergenController::class, 'allergens']);  // Listar todos los alérgenos
Route::post('restaurants/{restaurant}/reservations', [ReservationController::class, 'store']);  // Crear nueva reserva (privado)


// Rutas públicas para mostrar restaurantes, menús y platos
Route::get('/restaurants', [RestaurantController::class, 'index']);  // Listar todos los restaurantes
Route::get('/restaurants/{id}', [RestaurantController::class, 'show']);  // Mostrar un restaurante específico
Route::get('restaurants/{restaurant}/menu-categories', [MenuCategoryController::class, 'index']);  // Listar categorías de menú en un restaurante
Route::get('restaurants/{restaurant}/menu-categories/{menuCategory}', [MenuCategoryController::class, 'show']);  // Mostrar una categoría de menú específica
Route::get('menu-categories/{category}/menu-items', [MenuItemController::class, 'index']);  // Listar platos en una categoría
Route::get('restaurants/{restaurant}/menu-categories/{category}/menu-items/{menuItem}', [MenuItemController::class, 'show']);  // Mostrar un plato específico

// Rutas públicas para ver horarios de los restaurantes
Route::get('restaurants/{restaurant}/schedules', [RestaurantScheduleController::class, 'index']);  // Listar horarios de un restaurante

// Rutas públicas para ver reservas (por ejemplo, en caso de mostrar en frontend público)
Route::get('restaurants/{restaurant}/reservations', [ReservationController::class, 'index']);  // Listar reservas de un restaurante (público para ver)

// Rutas protegidas por Sanctum (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {

    // Rutas para usuarios autenticados
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas para cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rutas para restaurantes (protegidas)
    Route::post('/restaurants', [RestaurantController::class, 'store']);
    Route::put('/restaurants/{id}', [RestaurantController::class, 'update']);
    Route::delete('/restaurants/{id}', [RestaurantController::class, 'destroy']);

    // Rutas para categorías de menú dentro de un restaurante (protegidas)
    Route::post('restaurants/{restaurant}/menu-categories', [MenuCategoryController::class, 'store']);
    Route::put('restaurants/{restaurant}/menu-categories/{menuCategory}', [MenuCategoryController::class, 'update']);
    Route::delete('restaurants/{restaurant}/menu-categories/{menuCategory}', [MenuCategoryController::class, 'destroy']);

    // Rutas para platos dentro de una categoría de menú (protegidas)
    Route::post('menu-categories/{category}/menu-items', [MenuItemController::class, 'store']);
    Route::put('restaurants/{restaurant}/menu-categories/{category}/menu-items/{menuItem}', [MenuItemController::class, 'update']);
    Route::delete('restaurants/{restaurant}/menu-categories/{category}/menu-items/{menuItem}', [MenuItemController::class, 'destroy']);

    // Rutas para reservas (protegidas)
    Route::put('restaurants/{restaurant}/reservations/{reservation}', [ReservationController::class, 'update']);  // Actualizar una reserva (privado)
    Route::delete('restaurants/{restaurant}/reservations/{reservation}', [ReservationController::class, 'destroy']);  // Eliminar una reserva (privado)

    // Rutas para favoritos (protegidas)
    Route::get('favorites', [FavoriteController::class, 'index']);  // Listar favoritos del usuario (privado)
    Route::post('favorites', [FavoriteController::class, 'store']);  // Crear un favorito (privado)
    Route::delete('favorites/{favorite}', [FavoriteController::class, 'destroy']);  // Eliminar un favorito (privado)

    // Rutas para reseñas de platos (protegidas)
    Route::post('dish-reviews', [DishReviewController::class, 'store']);  // Crear reseña de plato (privado)
    Route::put('dish-reviews/{dishReview}', [DishReviewController::class, 'update']);  // Actualizar una reseña (privado)
    Route::delete('dish-reviews/{dishReview}', [DishReviewController::class, 'destroy']);  // Eliminar una reseña (privado)

    // Rutas protegidas para horarios de los restaurantes (administración)
    Route::post('restaurants/{restaurant}/schedules', [RestaurantScheduleController::class, 'store']);  // Crear o actualizar horarios de un restaurante (protegido)
    Route::delete('restaurants/{restaurant}/schedules/{schedule}', [RestaurantScheduleController::class, 'destroy']);  // Eliminar un horario específico (protegido)

    // Rutas para mesas de un restaurante (protegidas)
    Route::get('restaurants/{restaurant}/tables', [TableController::class, 'index']);  // Listar mesas de un restaurante (protegido)
    Route::post('restaurants/{restaurant}/tables', [TableController::class, 'store']);  // Crear una mesa en un restaurante (protegido)
    Route::put('restaurants/{restaurant}/tables/{table}', [TableController::class, 'update']);  // Actualizar una mesa específica (protegido)
    Route::delete('restaurants/{restaurant}/tables/{table}', [TableController::class, 'destroy']);  // Eliminar una mesa específica (protegido)

});

