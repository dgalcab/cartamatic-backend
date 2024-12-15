<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePopularTimesTable extends Migration
{
    public function up()
    {
        Schema::create('popular_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id');  // Relación con restaurantes
            $table->enum('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('popularity_percentage');  // Popularidad del 0 al 100%
            $table->timestamps();

            // Foreign key a restaurants
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('popular_times');
    }
}
