<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyReservationsTable extends Migration
{
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Verificar y eliminar las columnas 'date' y 'time' si existen
            if (Schema::hasColumn('reservations', 'date')) {
                $table->dropColumn('date');
            }

            if (Schema::hasColumn('reservations', 'time')) {
                $table->dropColumn('time');
            }

            // Añadir la nueva columna 'datetime' si no existe
            if (!Schema::hasColumn('reservations', 'datetime')) {
                $table->dateTime('datetime')->after('user_id');
            }

            // Añadir la nueva columna 'table_id' si no existe
            if (!Schema::hasColumn('reservations', 'table_id')) {
                $table->foreignId('table_id')->nullable()->constrained('tables')->onDelete('set null')->after('restaurant_id');
            }
        });
    }

    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Revertir la eliminación de 'date' y 'time'
            if (!Schema::hasColumn('reservations', 'date')) {
                $table->date('date')->after('user_id');
            }

            if (!Schema::hasColumn('reservations', 'time')) {
                $table->time('time')->after('date');
            }

            // Eliminar la columna 'datetime' si fue añadida
            if (Schema::hasColumn('reservations', 'datetime')) {
                $table->dropColumn('datetime');
            }

            // Eliminar la columna 'table_id' si fue añadida
            if (Schema::hasColumn('reservations', 'table_id')) {
                $table->dropForeign(['table_id']);
                $table->dropColumn('table_id');
            }
        });
    }
}
