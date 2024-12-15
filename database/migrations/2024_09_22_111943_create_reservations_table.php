<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');  // Relación con restaurante
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Usuario que reserva
            $table->date('date');  // Fecha de la reserva
            $table->time('time');  // Hora de la reserva
            $table->integer('num_people');  // Número de personas
            $table->enum('status', ['Pending', 'Confirmed', 'Cancelled'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
