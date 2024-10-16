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
            // Evitar que la startTime sea mayor que la endTime
            if ($this->startTime >= $this->endTime) {
                return false;
            }

            $existingAppointment = Appointments::select('number_of_assistants', 'start_time', 'end_time', 'user_id')
                ->from('appointments')
                ->where('date', $this->date)
                ->where('user_id', Auth::id())
                ->where(function ($query) {
                    $query->whereBetween('start_time', [$this->startTime, $this->endTime])
                        ->orWhereBetween('end_time', [$this->startTime, $this->endTime])
                        ->orWhere(function ($query) {
                            $query->where('start_time', '<=', $this->startTime)
                                ->where('end_time', '>=', $this->endTime);
                        });
                })
                ->first();

            if ($existingAppointment) {
                if ($existingAppointment->user_id == Auth::id()) {
                    // Permitir la actualización si solo se está cambiando el número de asistentes
                    if ($this->request->number_of_assistants != $existingAppointment->number_of_assistants) {
                        return true;
                    }

                    // Permitir la actualización si se está cambiando la hora de inicio o fin
                    if ($this->request->start_time != $existingAppointment->start_time || $this->request->end_time != $existingAppointment->end_time) {
                        // Comprobar si la nueva hora de inicio o fin no está en conflicto con otra cita
                        return !Appointments::select('start_time', 'end_time')
                            ->from('appointments')
                            ->where('date', $this->date)
                            ->where('user_id', Auth::id())
                            ->where(function ($query) {
                                $query->whereBetween('start_time', [$this->startTime, $this->endTime])
                                    ->orWhereBetween('end_time', [$this->startTime, $this->endTime])
                                    ->orWhere(function ($query) {
                                        $query->where('start_time', '<=', $this->startTime)
                                            ->where('end_time', '>=', $this->endTime);
                                    });
                            })
                            ->exists();
                    }

                    // Permitir la actualización si nada cambia
                    if ($this->request->start_time == $existingAppointment->start_time && $this->request->end_time == $existingAppointment->end_time && $this->request->number_of_assistants == $existingAppointment->number_of_assistants) {
                        return true;
                    }

                    // Permitir la actualización si el nuevo end_time es menor que el end_time existente
                    if ($this->request->end_time < $existingAppointment->end_time) {
                        // Comprobar primero que no esté dentro de otro rango de tiempo
                        return !Appointments::select('start_time', 'end_time')
                            ->from('appointments')
                            ->where('date', $this->date)
                            ->where('user_id', Auth::id())
                            ->where(function ($query) {
                                $query->whereBetween('start_time', [$this->startTime, $this->endTime])
                                    ->orWhereBetween('end_time', [$this->startTime, $this->endTime])
                                    ->orWhere(function ($query) {
                                        $query->where('start_time', '<=', $this->startTime)
                                            ->where('end_time', '>=', $this->endTime);
                                    });
                            })
                            ->exists();
                    }
                } else {
                    // No permitir la actualización si es un usuario diferente
                    return false;
                }
            }

            // Verificar que no se pueda ingresar una actualización si existe dentro de un rango previamente establecido
            return !Appointments::select('start_time', 'end_time')
                ->from('appointments')
                ->where('date', $this->date)
                ->where('user_id', Auth::id())
                ->where(function ($query) {
                    $query->whereBetween('start_time', [$this->startTime, $this->endTime])
                        ->orWhereBetween('end_time', [$this->startTime, $this->endTime])
                        ->orWhere(function ($query) {
                            $query->where('start_time', '<=', $this->startTime)
                                ->where('end_time', '>=', $this->endTime);
                        });
                })
                ->exists();
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
