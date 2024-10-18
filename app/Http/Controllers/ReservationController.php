<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller {
    public function calculateTime(Request $request): JsonResponse {
        // Definimos las constantes de tiempo y capacidad por grupo
        $tiempoPorGrupo = 25; // minutos
        $capacidadPorGrupo = 10; // personas por grupo

        // Calculamos el número de grupos necesarios
        $gruposNecesarios = ceil($request->number_of_assistants / $capacidadPorGrupo);
        Log::info("Grupos necesarios: $gruposNecesarios");

        // Si hay más salas disponibles, optimizamos las sesiones dividiéndolas entre las salas
        $sesionesSimultaneas = 2; // sesiones por sala

        // El tiempo total será el número de rondas multiplicado por el tiempo por grupo
        $tiempoTotal = $gruposNecesarios * $tiempoPorGrupo;
        Log::info("Tiempo total: $tiempoTotal");

        // Obtenemos la hora de inicio
        $horaInicio = $this->getEndTimeForDay($request->date);
        Log::info('Query result: ' . json_encode($horaInicio));

        if (empty($horaInicio)) {
            return response()->json([
                "message" => "No end time found for the given date",
                "success" => false,
            ]);
        }

        // Retornar la hora de inicio más el tiempo total para que sea la hora de fin
        return response()->json([
            // Sumar una hora a $horaInicio para que sea la hora de inicio
            "start_time" => date('H:i:s', strtotime($horaInicio->end_time) + 3600),
            "end_time" => date('H:i:s', strtotime($horaInicio->end_time) + 3600 + ($tiempoTotal * 60)),
            "tiempoTotal" => $tiempoTotal,
            "sesionesSimultaneas" => $sesionesSimultaneas,
            "success" => true,
        ]);
    }

    private function getEndTimeForDay($date) {
        $result = DB::table('appointments')
            ->select('end_time')
            ->where('date', $date)
            ->orderBy('end_time', 'desc')
            ->first();

        Log::info("Resultados de la consulta de end_time para la fecha $date: ", (array)$result);

        return $result;
    }
}
