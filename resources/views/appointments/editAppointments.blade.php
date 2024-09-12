@extends('layouts.layout')

@section('content')

    <div class="container-sm" style="margin-top: 70px; margin-bottom: 70px; max-width: 950px;">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8 bg-white p-5 text-center">
                <h2>Editar datos de la Cita</h2>

                <form id="updateForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group position-relative p-2">
                        <p class="text-start">Fecha</p>
                        <input type="date" class="form-control" id="date" name="date" style="padding-left: 30px;">
                    </div>

                    <div class="form-group position-relative p-2">
                        <p class="text-start">Hora</p>
                        <input type="time" class="form-control" id="time" name="time" style="padding-left: 30px;">
                    </div>

                    <div class="form-group position-relative p-2">
                        <p class="text-start">Número de Asistentes</p>
                        <input type="number" class="form-control" id="number_of_assistants" name="number_of_assistants"
                               style="padding-left: 15px;" min="1" max="20">
                    </div>

                    @if(Auth::user()->roles_id == 1)
                        <div class="form-group position-relative p-2">
                            <p class="text-start">Responsable</p>
                            <input type="text" class="form-control" id="name"
                                   style="padding-left: 15px;" min="1" max="20" readonly>
                        </div>
                    @endif
                </form>
                <div id="actionsButtons" class="row"></div>
            </div>
            <div class="col-2"></div>
        </div>
    </div>

    <script type="module" src="{{asset("js/utils/alert.js")}}"></script>
    <script type="module" src="{{asset("js/utils/api.js")}}"></script>
    <script type="module" src="{{ asset('js/appointments/viewAppointment.js') }}"></script>

@endsection
