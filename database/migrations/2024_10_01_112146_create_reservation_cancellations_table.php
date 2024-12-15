<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationCancellationsTable extends Migration
{
    public function up()
    {
        Schema::create('reservation_cancellations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id');  // Relación con la reserva cancelada
            $table->text('cancellation_reason')->nullable();  // Motivo de la cancelación
            $table->dateTime('cancellation_date');  // Fecha y hora de la cancelación
            $table->timestamps();

            // Foreign key a reservations
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservation_cancellations');
    }
}
