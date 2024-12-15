<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class UpdateReservationsStatus extends Command
{
    protected $signature = 'reservations:update-status';
    protected $description = 'Actualiza el estado de las reservas según la hora actual';

    public function handle()
    {
        // Buscar reservas confirmadas cuyo tiempo haya pasado y no estén ya en uso
        $now = Carbon::now();
        $reservations = Reservation::where('status', 'Confirmed')
            ->where('datetime', '<=', $now->subMinutes(15)) // Hace 15 minutos o más
            ->where('status', '!=', 'In Use') // Asegúrate de que no esté ya marcada como "In Use"
            ->get();

        foreach ($reservations as $reservation) {
            $reservation->status = 'In Use';
            $reservation->save();
        }

        $this->info('Estado de las reservas actualizado correctamente');
    }
}
