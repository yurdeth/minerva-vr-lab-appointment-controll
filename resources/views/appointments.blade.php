<?php
    if (!isset($dashboard)) {
        $layout = 'layouts.layout';
    }else{
        $layout = 'Administración.dashboard';
    }
?>

@extends($layout)
@vite(['resources/js/app.js'])

@section('content')

    <style>
        .card-body {
            margin-bottom: 8rem;
        }
    </style>

    <div class="container-xl" style="margin-top: 70px; margin-bottom: 70px;">
        <div class="row justify-content-between">
            <div class="col-auto">
                <a href="{{ route("agendar") }}" class="btn btn-success position-relative">
                    <i class="fa fa-address-book"></i>
                    <span class="tooltip-text">Agregar cita </span>
                </a>
                <a href="{{ route("export") }}" class="btn btn-info position-relative">
                    <i class="fa fa-file-excel"></i>
                    <span class="tooltip-text">Generar Reporte en Excel</span>
                </a>
                <a href="{{ route("pdf") }}" class="btn btn-danger position-relative">
                    <i class="fa fa-file-pdf"></i>
                    <span class="tooltip-text">Generar Reporte en PDF</span>
                </a>
            </div>

            <div class="col-auto">
                <form action="" id="searchForm">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-xl" style="margin-top: 20px; margin-bottom: 70px;">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h2 class="text-center">Todas tus Citas</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="height: 100%; width: 100%;">
                            <table id="appointmentsTable" class="table table-bordered text-center"
                                   style="width: 100%; margin-bottom: 0;">
                                <thead class="table-avatar">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Encargado</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Carrera</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Hora</th>
                                    <th scope="col">Participantes</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="module" src="{{asset("js/utils/alert.js")}}"></script>
    <script type="module" src="{{asset("js/utils/api.js")}}"></script>
    <script type="module" src="{{ asset('js/appointments.js') }}"></script>

    <br><br>

@endsection
