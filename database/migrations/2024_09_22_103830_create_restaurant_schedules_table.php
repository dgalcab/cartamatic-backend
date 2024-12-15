<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('restaurant_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('day_of_week')->check('day_of_week BETWEEN 0 AND 6');
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurant_schedules');
    }
}
