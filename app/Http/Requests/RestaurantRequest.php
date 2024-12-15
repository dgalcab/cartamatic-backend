<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        // Verificar si es una solicitud de creación o actualización
        $restaurantId = $this->route('id') ?? null;
        $userId = auth()->id();  // Obtener el ID del usuario autenticado

        return [
            'name' => 'required|string|max:255|unique:restaurants,name,' . $restaurantId . ',id,user_id,' . $userId,
            'location' => 'required|string|max:255',  // No es necesario hacerla única
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'website_url' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
