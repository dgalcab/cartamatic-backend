<?php

namespace App\Http\Controllers;

use App\Models\DishReview;
use Illuminate\Http\Request;

class DishReviewController extends Controller
{
    public function index()
    {
        return DishReview::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'menu_item_id' => 'required|exists:menu_items,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
        ]);

        return DishReview::create($data);
    }

    public function show($id)
    {
        return DishReview::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $dishReview = DishReview::findOrFail($id);

        $data = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
        ]);

        $dishReview->update($data);
        return $dishReview;
    }

    public function destroy($id)
    {
        $dishReview = DishReview::findOrFail($id);
        $dishReview->delete();
        return response()->json(['message' => 'Dish review deleted successfully.']);
    }
}
