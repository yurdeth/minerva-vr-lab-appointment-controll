@extends('Administración.dashboard')

{{--Section para poder trabajar con layout --}}

@section('content')

    <style>
        .custom-gap {
            display: flex;
            justify-content: start; /* Alinea los botones a la izquierda */
            gap: 15px; /* Espaciado entre los botones */
        }

        .custom-gap a {
            /* Asegúrate de que no haya margen adicional en los botones */
            margin: 0;
        }

        .btn {
            border: none; /* Elimina el borde del botón */
            text-decoration: none; /* Elimina el subrayado del texto */
            display: inline-flex; /* Asegura que el contenido se alinee correctamente */
            align-items: center; /* Centra verticalmente el contenido */
        }

        .btn i {
            margin-right: 5px; /* Ajusta el espacio entre el ícono y el texto */
            font-size: 15px; /* Ajusta el tamaño del ícono si es necesario */
        }

        /* Opcional: Estilo para la clase tooltip-text si es necesario */
        .tooltip-text {
            font-size: 13px;
            margin-left: 5px; /* Ajusta el espaciado del texto en el botón */
        }

    </style>

    <link rel="stylesheet" href="{{ asset('CSS/administracion/inventario.css')}}">
    <link rel="stylesheet" href="{{ asset('CSS/administracion/tables.css')}}">

    <div class="container-xl">
        <div class="d-flex justify-content-start custom-gap" style="margin-top: 15px;">
            <a href="#" class="btn btn-success">
                <i class="fa fa-graduation-cap"></i>
                <span class="tooltip-text">Agregar Carrera</span>
            </a>
            <a href="#" class="btn btn-success">
                <i class="fa fa-building"></i>
                <span class="tooltip-text">Agregar Departamento</span>
            </a>
        </div>
    </div>

    <!--Titulo de la vista -->
    <div class="container-fluid">
        <div class="title">
            <h2 style="color: black;">Registro de Carreras</h2>
        </div>
    </div>
    <!-- Contenido de la vista -->
    <div class="contentInventario">
        <form method="get" id="form_carreras">
            @csrf
            <div class="opcionesInventario">

                <!-- <div class="col">
                    <p>Tipo de Carrera</p>
                    <input type="text" name="tipo_carrera" class="form-control" id="tipo_carrera" placeholder="Ingrese tipo de carrera" style="width: 400px; padding: 10px;">
                </div>

                <div class="col">
                    <p>Departamento</p>
                    <select name="status" class="form-select status" id="status">
                        <option value="0">Seleccionar departamento</option>
                    </select>
                </div> -->

                <div class="card-body">
                    <div class="table-responsive" style="height: 100%; width: 100%;">
                        <table class="table table-bordered text-center" id="usersTable" style="width: 100%; margin-bottom: 0;">
                            <thead class="table-avatar">
                            <tr>
                                <th scope="col">Departamento</th>
                                <th scope="col">Carrera</th>
                                <th scope="col">Acciones</th>
                            </tr>
                            </thead>
                            <tbody id=""></tbody>
                        </table>
                    </div>
                </div>

                <!-- <div class="buttonContainer">
                    <input type="submit" value="Registrar Carrera" class="btn-success" id="submit">
                </div> -->

            </div>
        </form>
    </div>
@endsection
