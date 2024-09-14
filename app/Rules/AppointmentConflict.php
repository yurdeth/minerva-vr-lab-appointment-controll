<?php

namespace App\Rules;

use App\Models\Appointments;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use DateTime;
use Illuminate\Support\Facades\Auth;

class AppointmentConflict implements ValidationRule {

    protected $date;
    protected $time;
    protected $request;

    public function __construct($request, $date, $time) {
        $this->request = $request;

        $this->date = $date;

        //Dividir time por ":"
        if (count(explode(":", $time)) == 2) {
            $this->time = $time . ":00";
        } else {
            $this->time = $time;
        }
    }

    public function passes($attribute, $value): bool {
        if($this->request){
            $userAppointmentsExists = Appointments::where('user_id', $this->request->user_id)->exists();
            if ($userAppointmentsExists) {
                return false;
            }
            return true;
        }

        $exactMatchExists = Appointments::where('date', $this->date)
            ->where('time', $this->time)
            ->exists();

        if ($exactMatchExists) {
            return false;
        }

        $startTime = new DateTime("{$this->date} {$this->time}");
        $startTime->modify('-59 minutes');
        $endTime = new DateTime("{$this->date} {$this->time}");
        $endTime->modify('+59 minutes');

        $timeRangeConflictExists = Appointments::where('date', $this->date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('time', [$startTime->format('H:i:s'), $endTime->format('H:i:s')]);
            })
            ->exists();

        return !$timeRangeConflictExists;
    }

    public function message() {
        return 'Ya tienes una cita registrada en esta fecha y hora, o en el rango de una hora.';
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void {

        if (!$this->passes($attribute, $value)) {
            $fail($this->message());
        }
    }
}
