<?php
    if (!isset($dashboard)) {
        $layout = 'layouts.layout';
    }else{
        $layout = 'Administración.dashboard';
    }
?>

@extends($layout)

@section('content')
    <link rel="stylesheet" href="{{ asset('CSS/General/citas.css')}}">

    <!-- Contenedor principal -->
     <div class="container-xl">

        <!-- Contenedor de opciones -->
         <div class="opcionesCitas">
            <div class="col-auto">
                <a href="{{ route("agendar") }}" class="btn-success">
                            <i class="fa fa-address-book"></i>
                            <span class="tooltip-text">Agregar cita </span>
                </a>
            </div>
            <div class="col-auto">
                <a href="{{ route("export") }}" class="btn-info">
                        <i class="fa fa-file-excel"></i>
                        <span class="tooltip-text">Generar Reporte en Excel</span>
                </a>
            </div>
            <div class="col-auto">
                <a href="{{ route("pdf") }}" class="btn-danger">
                            <i class="fa fa-file-pdf"></i>
                            <span class="tooltip-text">Generar Reporte en PDF</span>
                </a>
            </div>
         </div>

         <!-- Parte de la busqueda -->
          <div class="busquedaCitas">
            <form action="" id="searchForm">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder=" Buscar...">
                            <button type="submit" class="btn-primary">Buscar</button>
                        </div>
                </form>
          </div>

          <!-- Parte de la tabla -->
           <div class="contentCita">
                <div class="card">
                    <div class="card-header bg-white">
                            <h2 class="titleCitas">Todas tus Citas</h2>
                    </div>

                    <div class="card-body">
                        <div class="rowTable">
                            <table id="appointmentsTable" class="table-bordered">
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


    <script type="module" src="{{asset("js/utils/alert.js")}}"></script>
    <script type="module" src="{{asset("js/utils/api.js")}}"></script>
    <script type="module" src="{{ asset('js/appointments.js') }}"></script>

    <br><br>

@endsection
