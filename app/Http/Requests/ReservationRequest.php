<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function authorize()
    {
        // Puedes manejar la autorización si es necesario
        return true;
    }

    public function rules()
    {
        return [
            'restaurant_id' => 'required|exists:restaurants,id',
            'num_people' => 'required|integer|min:1|max:6',
            'datetime' => 'required|date_format:Y-m-d H:i',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'required|string|max:20',
        ];
    }
}
