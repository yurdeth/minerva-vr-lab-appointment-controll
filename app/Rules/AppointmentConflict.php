<?php

namespace App\Rules;

use App\Models\Appointments;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AppointmentConflict implements ValidationRule {

    protected $date;
    protected $startTime;
    protected $endTime;
    protected $request;
    protected $action;
    protected $message;

    public function __construct($request, string $date, string $startTime, string $endTime, string $action) {
        $this->request = $request;
        $this->date = $date;
        $this->action = $action;


        // Dividir time por ":"
        $this->startTime = count(explode(":", $startTime)) == 2 ? $startTime . ":00" : $startTime;
        $this->endTime = count(explode(":", $endTime)) == 2 ? $endTime . ":00" : $endTime;
    }

    private function passes($attribute, $value): bool {
        if ($this->action == 'update') {
            //Verificar si la cita ya existe en el mismo horario de la fecha
            $appointment = Appointments::select('date', 'start_time', 'end_time', 'user_id')
                ->from('appointments')
                ->where('date', $this->date)
                ->first(); // Use first() to get a single record

            if ($appointment && $appointment->date == $this->date) {
                if ($appointment->start_time == $this->startTime && $appointment->end_time == $this->endTime) {
                    return false; // Exact match
                }

                if ($this->startTime > $appointment->start_time && $this->endTime > $appointment->end_time) {
                    return true; // New appointment ends after the existing one
                }

                if ($appointment->start_time > $this->startTime && $appointment->end_time == $this->endTime) {
                    return false; // Existing appointment starts after the new one and ends at the same time
                }

                if ($appointment->start_time == $this->startTime && $appointment->end_time < $this->endTime) {
                    return false; // Existing appointment ends before the new one
                }

                if ($appointment->start_time > $this->startTime && $appointment->end_time < $this->endTime) {
                    return false; // Existing appointment is completely within the new one
                }

                return true; // No conflicts
            }

            return true; // No conflicts
        }

        Log::info($this->startTime);
        Log::info($this->endTime);

        if ($this->startTime < '08:00:00' || $this->endTime > '17:00:00' || $this->startTime >= $this->endTime) {
            $this->message = "Error: el horario de atención es de 8:00 AM a 5:00 PM";
            return false;
        }

        $appointments = Appointments::select('start_time', 'end_time')
            ->from('appointments')
            ->where('date', $this->date)
            ->where('user_id', Auth::id())
            ->get();

        foreach ($appointments as $appointment) {
            $start = new DateTime($appointment->start_time);
            $end = new DateTime($appointment->end_time);
            $end->modify('-1 minute');
            $startRequest = new DateTime($this->startTime);
            $endRequest = new DateTime($this->endTime);

            $interval = $startRequest->diff($endRequest);
            $minutes = ($interval->h * 60) + $interval->i;

            // Maximo 72 personas
            if ($this->request->numbre_of_assistants > 72) {
                $this->message = "Error: el límite de participantes por cita es de 72 personas";
                return false;
            }

            if ($minutes < 25) {
                $this->message = "El tiempo mínimo para una cita es de 25 minutos";
                return false;
            }

            // Tiempo de espera de una hora entre citas (debe haber un margen de 1 hora entre end_time y el siguiente start_time)
            if ($startRequest->format('H:i:s') < $end->format('H:i:s')) {
                $this->message = "Error: debe haber un margen de 1 hora entre citas";
                return false;
            }

            if ($startRequest >= $start && $startRequest <= $end) {
                $this->message = "Error: ya existe una cita en ese horario";
                return false;
            }

            if ($endRequest >= $start && $endRequest <= $end) {
                $this->message = "Error: ya existe una cita en ese horario";
                return false;
            }

            if ($startRequest <= $start && $endRequest >= $end) {
                $this->message = "Error: ya existe una cita en ese horario";
                return false;
            }

        }

        return true;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if (!$this->passes($attribute, $value)) {
            $fail($this->message);
        }
    }
}
