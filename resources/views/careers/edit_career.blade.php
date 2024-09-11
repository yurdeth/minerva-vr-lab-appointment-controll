@extends('Administración.dashboard')

{{--Section para poder trabajar con layout --}}

@section('content')

    <link rel="stylesheet" href="{{ asset('CSS/administracion/inventario.css')}}">
    <link rel="stylesheet" href="{{ asset('CSS/administracion/tables.css')}}">

    <!--Titulo de la vista -->
    <div class="container-fluid" style="margin-top: 5px;">
        <div class="title">
            <h2 style="color: black;">Editar datos de la carrera</h2>
        </div>
    </div>
    <!-- Contenido de la vista -->
    <div class="contentInventario">
        <form method="get" id="form_carreras">
            @csrf
            <div class="opcionesInventario">

                <div class="col">
                    <p>Seleccione el departamento</p>
                    <select name="status" class="form-select status" id="status">
                        <option value="0">Seleccionar departamento</option>
                    </select>
                </div>

                <div class="col">
                    <p>Nombre de la carrera</p>
                    <input type="text" name="tipo_carrera" class="form-control" id="tipo_carrera" placeholder="Ingrese tipo de carrera" style="width: 400px; padding: 10px;">
                </div>

                <div class="buttonContainer">
                    <input type="submit" value="Registrar Carrera" class="btn-success" id="submit">
                </div>

            </div>
        </form>
    </div>
@endsection
