<?php

namespace App\Rules;

use App\Models\Appointments;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ParticipantsConflict implements ValidationRule {

    protected $participants;
    protected $start_time;
    protected $end_time;

    /**
     * @param $participants
     */
    public function __construct($participants, $start_time, $end_time) {
        $this->participants = $participants;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
    }

    private function passes($attribute, $value): bool {
        $timeDifferece = strtotime($this->end_time) - strtotime($this->start_time);

        switch ($timeDifferece) {
            case '1': //<- Tomando en cuenta la sesión de 30 minutos para 20 participantes (10/sala): en 1 hora se pueden atender 40 participantes
                if ($this->participants > 40) {
                    return false;
                }
                break;
            case '2': //<- Tomando en cuenta la sesión de 30 minutos para 40 participantes (10/sala): en 1 hora se pueden atender 80 participantes
                if ($this->participants > 80) {
                    return false;
                }
                break;
            case '3': //<- Tomando en cuenta la sesión de 30 minutos para 60 participantes (10/sala): en 1 hora se pueden atender 120 participantes
                if ($this->participants > 120) {
                    return false;
                }
                break;
            case '4': //<- Tomando en cuenta la sesión de 30 minutos para 80 participantes (10/sala): en 1 hora se pueden atender 160 participantes
                if ($this->participants > 150) {
                    return false;
                }
                break;
            default: //<- No se qué pasará, pero estoy muy nervioso xd
                return true;
        }

        return true;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if (!$this->passes($attribute, $value)) {
            $fail("El número de participantes excede el límite para el tiempo seleccionado");
        }
    }
}
