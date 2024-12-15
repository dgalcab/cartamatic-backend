<?php
namespace App\Providers;

use App\Models\Restaurant;
use App\Policies\RestaurantPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Aquí mapeamos el modelo Restaurant con su Policy
        Restaurant::class => RestaurantPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        // Aquí puedes registrar gates adicionales si los necesitas
    }
}
