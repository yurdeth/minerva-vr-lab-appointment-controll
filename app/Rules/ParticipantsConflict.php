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
    $timeDifference = (strtotime($this->end_time) - strtotime($this->start_time)) / 3600;

    switch ($timeDifference) {
        case 1: // 1 hour session for 20 participants (10 per room): 40 participants in 1 hour
            if ($this->participants > 40) {
                return false;
            }
            break;
        case 2: // 2 hour session for 40 participants (10 per room): 80 participants in 1 hour
            if ($this->participants > 80) {
                return false;
            }
            break;
        case 3: // 3 hour session for 60 participants (10 per room): 120 participants in 1 hour
            if ($this->participants > 120) {
                return false;
            }
            break;
        case 4: // 4 hour session for 80 participants (10 per room): 160 participants in 1 hour
            if ($this->participants > 150) {
                return false;
            }
            break;
        default: // Default case
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
