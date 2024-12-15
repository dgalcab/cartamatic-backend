<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('restaurant_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Usuario que deja la reseña
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');  // Relación con el restaurante
            $table->tinyInteger('rating')->check('rating BETWEEN 1 AND 5');  // Calificación entre 1 y 5
            $table->text('comment')->nullable();  // Comentario opcional
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurant_reviews');
    }
}
