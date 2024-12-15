<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesTable extends Migration
{
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id');  // Relación con restaurantes
            $table->integer('capacity');  // Capacidad de personas por mesa
            $table->string('location')->nullable();  // Interior, exterior, terraza, etc.
            $table->timestamps();

            // Foreign key a restaurantes
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tables');
    }
}
