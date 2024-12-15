<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantsTable extends Migration
{
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // El propietario del restaurante
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location');
            $table->string('phone', 50);
            $table->string('email', 100);
            $table->string('website_url')->nullable();
            $table->string('logo_url')->nullable();  // URL del logo del restaurante
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurants');
    }
}
