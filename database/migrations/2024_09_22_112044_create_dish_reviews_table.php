<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDishReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('dish_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Usuario que deja la reseña
            $table->foreignId('menu_item_id')->constrained('menu_items')->onDelete('cascade');  // Relación con el plato
            $table->tinyInteger('rating')->check('rating BETWEEN 1 AND 5');  // Calificación
            $table->text('comment')->nullable();  // Comentario opcional
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dish_reviews');
    }
}
