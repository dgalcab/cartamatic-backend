<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class RestaurantReviewController extends Controller
{
    // Listar las reseñas de un restaurante
    public function index($restaurant_id)
    {
        $reviews = Review::where('restaurant_id', $restaurant_id)->get();
        return response()->json($reviews);
    }

    // Crear una nueva reseña para un restaurante
    public function store(Request $request, $restaurant_id)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'comment' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = new Review();
        $review->restaurant_id = $restaurant_id;
        $review->user_id = $validated['user_id'];
        $review->comment = $validated['comment'];
        $review->rating = $validated['rating'];
        $review->save();

        return response()->json($review, 201);
    }

    // Mostrar una reseña específica
    public function show($id)
    {
        $review = Review::findOrFail($id);
        return response()->json($review);
    }

    // Actualizar una reseña
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $validated = $request->validate([
            'comment' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review->update($validated);
        return response()->json($review);
    }

    // Eliminar una reseña
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(null, 204);
    }
}
