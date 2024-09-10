@extends('Administración.dashboard')

{{--Section para poder trabajar con layout --}}

@section('content')
    <link rel="stylesheet" href="{{ asset('CSS/administracion/inventario.css')}}">
    <!--Titulo de la vista -->
    <div class="container-fluid">
        <div class="title">
            <h2>Registro de Carreras</h2>
        </div>
    </div>
    <!-- Contenido de la vista -->
    <div class="contentInventario">
        <form method="get" id="form_carreras">
            @csrf
            <div class="opcionesInventario">

                <div class="col">
                    <p>Tipo de Carrera</p>
                    <select name="status" class="form-select status" id="status">
                        <option value="0">Seleccionar tipo de carrera</option>
                    </select>
                </div>

                <div class="col">
                    <p>Departamento</p>
                    <select name="status" class="form-select status" id="status">
                        <option value="0">Seleccionar departamento</option>
                    </select>
                </div>

                <div class="buttonContainer">
                    <input type="submit" value="Registrar Carrera" class="btn-success" id="submit">
                </div>

            </div>
        </form>
    </div>
@endsection
