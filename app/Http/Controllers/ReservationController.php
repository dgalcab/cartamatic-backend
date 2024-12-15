<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Http\Requests\ReservationRequest;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    // Listar todas las reservas de un restaurante
    public function index(Request $request, $restaurantId)
    {
        $date = $request->query('date');

        $reservations = $date
            ? Reservation::where('restaurant_id', $restaurantId)->whereDate('datetime', $date)->get()
            : Reservation::where('restaurant_id', $restaurantId)->get();

        return response()->json($reservations, 200);
    }

    // Crear una nueva reserva
    public function store(ReservationRequest $request)
    {
        $validated = $request->validated();

        $restaurantId = $validated['restaurant_id'];
        $datetime = $validated['datetime'];
        $clientEmail = $validated['client_email'];
        $numPeople = $validated['num_people'];

        // Verificar si el cliente ya tiene una reserva para el mismo día y tramo de hora
        $existingReservation = Reservation::where('restaurant_id', $restaurantId)
            ->where('client_email', $clientEmail)
            ->whereDate('datetime', '=', date('Y-m-d', strtotime($datetime)))
            ->where(function ($query) use ($datetime) {
                $query->whereTime('datetime', '<=', date('H:i:s', strtotime($datetime . ' + 1 hour 45 minutes')))
                    ->whereTime('datetime', '>=', date('H:i:s', strtotime($datetime . ' - 1 hour 45 minutes')));
            })
            ->first();

        if ($existingReservation) {
            return response()->json(['message' => 'Ya tienes una reserva en ese horario.'], 400);
        }

        // Verificar si el restaurante está abierto en ese momento
        if (!$this->reservationService->isRestaurantOpen($restaurantId, $datetime)) {
            return response()->json(['message' => 'El restaurante está cerrado en ese momento.'], 400);
        }

        // Buscar la mejor mesa disponible
        $table = $this->reservationService->findBestTable($restaurantId, $datetime, $numPeople);

        if (!$table) {
            return response()->json(['message' => 'No hay mesas disponibles en ese horario.'], 400);
        }

        // Crear la reserva
        $reservation = Reservation::create([
            'restaurant_id' => $restaurantId,
            'table_id' => $table->id,
            'datetime' => $datetime,
            'num_people' => $numPeople,
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_phone' => $validated['client_phone'],
            'status' => 'Confirmed',
        ]);

        return response()->json(['message' => 'Reserva confirmada con éxito.', 'reservation' => $reservation], 201);
    }

    // Actualizar una reserva
    public function update(ReservationRequest $request, $restaurantId, $reservationId)
    {
        $reservation = Reservation::where('restaurant_id', $restaurantId)
            ->where('id', $reservationId)
            ->first();

        if (!$reservation) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        // Actualizar los datos de la reserva
        $validated = $request->validated();
        $reservation->update($validated);

        return response()->json(['message' => 'Reserva actualizada con éxito.', 'reservation' => $reservation], 200);
    }

    // Eliminar una reserva
    public function destroy($restaurantId, $reservationId)
    {
        $reservation = Reservation::where('restaurant_id', $restaurantId)
            ->where('id', $reservationId)
            ->first();

        if (!$reservation) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        $reservation->delete();

        return response()->json(['message' => 'Reserva eliminada con éxito.'], 200);
    }

    // Cambiar el estado a "Completed" cuando la reserva haya vencido
    public function completeExpiredReservations()
    {
        $now = Carbon::now();

        // Buscar reservas que hayan pasado su tiempo y estén confirmadas
        $expiredReservations = Reservation::where('status', 'Confirmed')
            ->where('datetime', '<', $now)
            ->get();

        // Actualizar el estado a "Completed"
        foreach ($expiredReservations as $reservation) {
            $reservation->update(['status' => 'Completed']);
        }

        return response()->json(['message' => 'Reservas vencidas marcadas como completadas.'], 200);
    }
}
