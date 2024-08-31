<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\Participants;
use App\Rules\AppointmentConflict;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class AppointmentsController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $appointments = (new Appointments)->GetAppointments();
        return response()->json([
            "data" => $appointments,
            "success" => true,
        ]);
    }
    //method to generate pdf
    public function pdf(){
        $appointments = (new Appointments)->GetAppointments();
        $pdf = Pdf::loadView('citaspdf',compact('appointments'));
        return $pdf->stream();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $validate = Validator::make($request->all(), [
            "date" => "required|date|after:today",
            "time" => ["required", new AppointmentConflict($request->date, $request->time)],
            "number_of_assistants" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "message" => "Error en los datos",
                "error" => $validate->errors(),
                "success" => false,
            ]);
        }

        $appointment = Appointments::create([
            'date' => $request->date,
            'time' => $request->time,
            'user_id' => Auth::id(),
        ]);

        $participants = Participants::create([
            'number_of_participants' => $request->number_of_assistants,
            'appointment_id' => $appointment->id
        ]);

        if (!$appointment || !$participants) {
            return response()->json([
                "message" => "Error al crear el registro",
                "success" => false,
            ], 500);
        }

        $appointment->save();
        $participants->save();

        return response()->json([
            "message" => "Cita registrada",
            "redirect_to" => 'index',
            "success" => true,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {
        try {
            $appointments = (new Appointments)->GetSpecificAppointment($id);

            if ($appointments->isEmpty()) {
                return redirect()->route('citas')->with('error', 'Cita no encontrada.');
            }

            return response()->json($appointments);
        } catch (\Exception $e) {
            return redirect()->route('citas')->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        $appointment = Appointments::find($id);
        $participants = Participants::where('appointment_id', $appointment->id)->first();

        $appointment->date = $request->date;
        $appointment->time = $request->time;
        $participants->number_of_participants = $request->number_of_assistants;

        $appointment->save();
        $participants->save();

        return redirect()->route('citas');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        $appointment = Appointments::find($id);
        if ($appointment) {
            $appointment->delete();
        }

        return redirect()->route('citas-ver');
    }

    /**
     * @throws \Exception
     */
    public function AvailableSchedules(Request $request){
        $date = $request->date;
        $schedules = (new Appointments)->GetAvailableSchedules($date);
        return response()->json($schedules);
    }
}
