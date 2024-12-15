<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoritesTable extends Migration
{
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Relación con el usuario
            $table->foreignId('restaurant_id')->nullable()->constrained()->onDelete('cascade');  // Relación con el restaurante (opcional)
            $table->foreignId('menu_item_id')->nullable()->constrained()->onDelete('cascade');  // Relación con el plato (opcional)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
    }
}
