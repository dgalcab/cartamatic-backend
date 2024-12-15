<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        return Favorite::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'restaurant_id' => 'nullable|exists:restaurants,id',
            'menu_item_id' => 'nullable|exists:menu_items,id',
        ]);

        return Favorite::create($data);
    }

    public function show($id)
    {
        return Favorite::findOrFail($id);
    }

    public function destroy($id)
    {
        $favorite = Favorite::findOrFail($id);
        $favorite->delete();
        return response()->json(['message' => 'Favorite deleted successfully.']);
    }
}
