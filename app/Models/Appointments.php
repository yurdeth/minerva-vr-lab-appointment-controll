<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Appointments extends Model {
    use HasFactory;

    protected $table = "appointments";
    protected $fillable = [
        "date",
        "time",
        "status",
        "user_id"
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function GetAppointments(){
        if (Auth::user()->roles_id == 1) {
            return DB::table('appointments')
                ->join('participants', 'appointments.id', '=', 'participants.appointment_id')
                ->join('users', 'appointments.user_id', '=', 'users.id')
                ->join('careers', 'users.career_id', '=', 'careers.id')
                ->join('departments', 'careers.department_id', '=', 'departments.id')
                ->select(
                    'appointments.id',
                    'participants.number_of_participants',
                    'users.name',
                    'careers.department_id',
                    'departments.department_name',
                    'users.career_id',
                    'careers.career_name',
                    'appointments.user_id',
                    'appointments.date',
                    'appointments.time'
                )
                ->get();
        } else {
            $userId = Auth::id(); // Obtener el ID del usuario autenticado

            return DB::table('appointments')
                ->where('appointments.user_id', $userId)
                ->join('participants', 'appointments.id', '=', 'participants.appointment_id')
                ->join('users', 'appointments.user_id', '=', 'users.id')
                ->join('careers', 'users.career_id', '=', 'careers.id')
                ->join('departments', 'careers.department_id', '=', 'departments.id')
                ->select([
                    'appointments.id',
                    'participants.number_of_participants',
                    'users.name',
                    'careers.department_id',
                    'departments.department_name',
                    'users.career_id',
                    'careers.career_name',
                    'appointments.date',
                    'appointments.time'
                ])
                ->get();
        }
    }

    public function GetSpecificAppointment($id){
        // Obtener el ID del usuario autenticado
        $userId = Auth::id();

        // Obtener la cita específica
        $appointment = DB::table('appointments')->where('id', $id)->first();

        // Verificar si la cita existe
        if (!$appointment) {
            return redirect()->route('citas')->with('error', 'Cita no encontrada.');
        }

        if (Auth::user()->roles_id == 1 || $appointment->user_id == $userId) {
            // Realizar la consulta detallada si el usuario tiene permisos
            return DB::table('appointments')
                ->where('appointments.id', $id)
                ->join('participants', 'appointments.id', '=', 'participants.appointment_id')
                ->join('users', 'appointments.user_id', '=', 'users.id')
                ->join('careers', 'users.career_id', '=', 'careers.id')
                ->join('departments', 'careers.department_id', '=', 'departments.id')
                ->select(
                    'appointments.id',
                    'participants.number_of_participants',
                    'users.name',
                    'careers.department_id',
                    'departments.department_name',
                    'users.career_id',
                    'careers.career_name',
                    'appointments.date',
                    'appointments.time'
                )
                ->get();
        } else {
            // Lanzar una excepción si el usuario no tiene permisos
            throw new \Exception("No tienes permisos para ver esta cita");
        }
    }

    /**
     * @throws \Exception
     */
    public function GetAvailableSchedules($date): \Illuminate\Support\Collection {
        try {
            return DB::table('appointments')
                ->where('date', $date)
                ->select('id', 'time', 'date')
                ->get();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
