<?php

namespace App\Policies;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RestaurantPolicy
{
    use HandlesAuthorization;

    // Determina si el usuario puede actualizar el restaurante
    public function update(User $user, Restaurant $restaurant)
    {
        return $user->id === $restaurant->user_id;
    }

    // Determina si el usuario puede eliminar el restaurante
    public function delete(User $user, Restaurant $restaurant)
    {
        return $user->id === $restaurant->user_id;
    }
}
