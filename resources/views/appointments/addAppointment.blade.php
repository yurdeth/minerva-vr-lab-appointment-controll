<!--Incluimos el header y footer del layout-->
@extends('layouts.layout')

{{--Section para poder trabajar con layout --}}
@section('content')

    <div class="container-sm"
         style="margin-top: 70px; margin-bottom: 70px; max-width: 950px;">

        <div class="row">
            <div class="col-2"></div>

            <div class="col-8 bg-white p-5 text-center">
                <h2>Formulario de Solicitud de Cita</h2>
                <form class="" method="post">
                    @csrf

                    <div class="form-group position-relative p-2">
                        <p class="text-start">Numero de Asistentes</p>
                        <input type="number" class="form-control" id="number_of_assistants" name="number_of_assistants"
                               placeholder="n° de personas (Máx. 20)" style="padding-left: 15px;" min="1" max="20" required>
                    </div>

                    <div class="form-group position-relative p-2">
                        <p class="text-start">Fecha</p>
                        <input type="date" class="form-control" name="date" id="date"
                               style="padding-left: 30px;" required onchange="showAvailableShedules()">
                    </div>

                    <div class="form-group position-relative p-2">
                        <p class="text-start">Hora</p>
                        <input type="time" class="form-control" id="time"
                               name="time" placeholder="" style="padding-left: 30px;" required>
                    </div>
                    <div class="form-group position-relative p-2 text-center" id="loadAvailableSchedules"></div>

                    <button type="submit" class="btn text-white m-2 form-control" id="submitButton" style="background-color: #660D04;">
                        Agendar
                    </button>
                </form>

            </div>
            <div class="col-2"></div>

        </div>

    </div>

    <script type="module" src="{{asset("js/utils/alert.js")}}"></script>
    <script type="module" src="{{asset("js/utils/api.js")}}"></script>
    <script type="module" src="{{ asset('js/appointments/addAppointment.js') }}"></script>
    <script src="{{ asset('js/appointments/showAvailableShedules.js') }}"></script>
@endsection
