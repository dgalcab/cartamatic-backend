<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->enum('status', ['Pending', 'Confirmed', 'Cancelled', 'Completed'])->default('Pending')->change();
        });
    }

    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->enum('status', ['Pending', 'Confirmed', 'Cancelled'])->default('Pending')->change();
        });
    }
};
