<!--Incluimos el header y footer del layout-->
@extends('layouts.layout')

{{--Section para poder trabajar con layout --}}
@section('content')
        <div class="container-fluid" style="margin-top: 70px; margin-bottom: 70px; max-width: 950px;">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 col-sm-12 bg-white p-5 text-center" style="box-shadow: 0 4px 8px rgba(93, 102, 107, 0.419), 0 6px 20px rgba(51, 152, 206, 0.419); border-radius: 6px;">
                    <h2>Formulario de Solicitud de Cita</h2>
                    <form class="" method="post">
                        @csrf
                        <div class="form-group position-relative p-2">
                            <p class="text-start">Numero de Asistentes</p>
                            <input type="number" class="form-control" id="number_of_assistants" name="number_of_assistants"
                                       placeholder="n° de personas (Máx. 150)" style="padding-left: 15px;" min="1" max="150"
                                       required>
                        </div>

                        <div class="form-group position-relative p-2">
                            <p class="text-start">Fecha</p>
                            <input type="date" class="form-control" name="date" id="date"
                                style="padding-left: 30px;" required onchange="showAvailableShedules()">
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group position-relative ps-3">
                                <p class="text-start">Hora de inicio</p>
                                <input type="time" class="form-control" id="start-time" name="start-time" required>
                            </div>
                            <div class="col-md-6 form-group position-relative pe-3">
                                <p class="text-start">Hora de finalización</p>
                                <input type="time" class="form-control" id="end-time" name="end-time" required>
                            </div>
                        </div>

                        <div class="form-group position-relative p-2 text-center" id="loadAvailableSchedules"></div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="modal-calendario">
                                    <!-- Modal para el calendario -->
                                    <div class="modal fade" id="calendarModal" aria-hidden="true"
                                              aria-labelledby="calendarModalLabel" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="calendarModalLabel">Calendario</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="calendar">
                                                        <h1>¡Hola Mundo!</h1>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal para la fecha seleccionada -->
                                    <div class="modal fade" id="dateModal" aria-hidden="true"
                                         aria-labelledby="dateModalLabel" tabindex="-1">
                                         <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="dateModalLabel">Fecha seleccionada</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p id="selectedDate"></p>
                                                </div>
                                            </div>
                                         </div>
                                    </div>

                                    <!-- Botón para abrir el modal del calendario -->
                                    <button type="button" class="btn btn-primary m-2 w-100" id="btnOpenCalendar"
                                        data-bs-toggle="modal" data-bs-target="#calendarModal">Mostrar calendario
                                    </button>

                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <button type="submit" class="btn text-white m-2 w-100" id="submitButton" style="background-color: #660D04;">
                                    Agendar
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    <script type="module" src="{{asset("js/utils/alert.js")}}"></script>
    <script type="module" src="{{asset("js/utils/api.js")}}"></script>
    <script type="module" src="{{ asset('js/appointments/addAppointment.js') }}"></script>
    <script src="{{ asset('js/appointments/showAvailableShedules.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"
            integrity="sha256-ZztCtsADLKbUFK/X6nOYnJr0eelmV2X3dhLDB/JK6fM=" crossorigin="anonymous"></script>
    <script src="{{ asset("js/calendar/calendar.js") }}"></script>
@endsection
