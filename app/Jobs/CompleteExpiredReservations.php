<?php

namespace App\Jobs;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CompleteExpiredReservations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $now = now();

        // Buscar reservas que hayan pasado su tiempo y estén confirmadas
        $expiredReservations = Reservation::where('status', 'Confirmed')
            ->where('datetime', '<', $now)
            ->get();

        // Actualizar el estado a "Completed"
        foreach ($expiredReservations as $reservation) {
            $reservation->update(['status' => 'Completed']);
        }
    }
}
