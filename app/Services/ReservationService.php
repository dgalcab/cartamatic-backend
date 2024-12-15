<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\RestaurantSchedule;

class ReservationService
{
    public function isRestaurantOpen($restaurantId, $datetime)
    {
        $dayOfWeek = date('l', strtotime($datetime));
        $time = date('H:i:s', strtotime($datetime));

        // Buscar el horario del restaurante para ese día de la semana
        $schedule = RestaurantSchedule::where('restaurant_id', $restaurantId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        return $schedule && $time >= $schedule->open_time && $time <= $schedule->close_time;
    }

    public function findBestTable($restaurantId, $datetime, $numPeople)
    {
        $tables = Table::where('restaurant_id', $restaurantId)
            ->where('capacity', '>=', $numPeople)
            ->orderBy('capacity', 'asc')
            ->get();

        foreach ($tables as $table) {
            if (!$this->isTableReserved($table->id, $datetime)) {
                return $table;
            }
        }

        return null;
    }

    public function isTableReserved($tableId, $reservationStart)
    {
        $reservationEnd = date('Y-m-d H:i:s', strtotime($reservationStart . ' + 1 hour 45 minutes'));

        return Reservation::where('table_id', $tableId)
            ->where('datetime', '<=', $reservationEnd)
            ->where('status', 'Confirmed')
            ->exists();
    }
}
