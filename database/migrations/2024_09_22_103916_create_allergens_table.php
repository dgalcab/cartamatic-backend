<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllergensTable extends Migration
{
    public function up()
    {
        Schema::create('allergens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon_url')->nullable();  // Icono SVG del alérgeno
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('allergens');
    }
}
