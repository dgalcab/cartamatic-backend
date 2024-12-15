<?php

namespace App\Http\Controllers;

use App\Models\RestaurantSchedule;
use Illuminate\Http\Request;

class RestaurantScheduleController extends Controller
{
    public function index($restaurantId)
    {
        return RestaurantSchedule::where('restaurant_id', $restaurantId)->get();
    }

    public function store(Request $request, $restaurantId)
    {
        // Validar los horarios enviados desde el frontend
        $request->validate([
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|integer|between:0,6',
            'schedules.*.open_time' => 'nullable|date_format:H:i',
            'schedules.*.close_time' => 'nullable|date_format:H:i',
        ]);

        // Eliminar horarios antiguos del restaurante para evitar duplicados
        RestaurantSchedule::where('restaurant_id', $restaurantId)->delete();

        // Recorrer los horarios y guardarlos en la base de datos
        foreach ($request->schedules as $schedule) {
            RestaurantSchedule::create([
                'restaurant_id' => $restaurantId,
                'day_of_week' => $schedule['day_of_week'],
                'open_time' => $schedule['open_time'] ?: null, // Si no hay hora, usar null
                'close_time' => $schedule['close_time'] ?: null, // Si no hay hora, usar null
            ]);
        }

        return response()->json(['message' => 'Horarios guardados correctamente'], 201);
    }

    public function update(Request $request, $restaurantId)
    {
        // Validar la entrada para asegurarse de que el formato es correcto
        $request->validate([
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|integer|between:0,6',
            'schedules.*.open_time' => 'nullable|date_format:H:i',
            'schedules.*.close_time' => 'nullable|date_format:H:i',
        ]);

        // Recorrer los horarios y actualizarlos o crearlos si no existen
        foreach ($request->schedules as $schedule) {
            $existingSchedule = RestaurantSchedule::where('restaurant_id', $restaurantId)
                ->where('day_of_week', $schedule['day_of_week'])
                ->first();

            // Si existe, actualizar
            if ($existingSchedule) {
                $existingSchedule->update([
                    'open_time' => $schedule['open_time'] ?: null, // Si no hay hora, usar null
                    'close_time' => $schedule['close_time'] ?: null, // Si no hay hora, usar null
                ]);
            } else {
                // Si no existe, crear uno nuevo
                RestaurantSchedule::create([
                    'restaurant_id' => $restaurantId,
                    'day_of_week' => $schedule['day_of_week'],
                    'open_time' => $schedule['open_time'] ?: null, // Si no hay hora, usar null
                    'close_time' => $schedule['close_time'] ?: null, // Si no hay hora, usar null
                ]);
            }
        }

        return response()->json(['message' => 'Horarios actualizados correctamente'], 200);
    }

    public function destroy($id)
    {
        $schedule = RestaurantSchedule::findOrFail($id);
        $schedule->delete();
        return response()->json(['message' => 'Horario eliminado correctamente.']);
    }
}
