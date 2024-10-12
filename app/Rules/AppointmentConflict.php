<?php

namespace App\Rules;

use App\Models\Appointments;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use DateTime;
use Illuminate\Support\Facades\Auth;

class AppointmentConflict implements ValidationRule {

    protected $date;
    protected $startTime;
    protected $endTime;
    protected $request;
    protected $action;

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
            // Evitar que la startTime sea mayor que la endtTime
            if ($this->startTime >= $this->endTime) {
                return false;
            }

            if($this->request){
                $userAppointmentsExists = Appointments::where('user_id', $this->request->user_id)->exists();
                if ($userAppointmentsExists) {
                    return false;
                }
                return true;
            }
            return false;
        }


        if ($this->startTime < '08:00:00' || $this->endTime > '17:00:00' || $this->startTime >= $this->endTime) {
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

            if ($startRequest >= $start && $startRequest <= $end) {
                return false;
            }

            if ($endRequest >= $start && $endRequest <= $end) {
                return false;
            }

            if ($startRequest <= $start && $endRequest >= $end) {
                return false;
            }
        }
        return true;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if (!$this->passes($attribute, $value)) {
            $fail('Ya tienes una cita registrada en este rango de horario.');
        }
    }
}
