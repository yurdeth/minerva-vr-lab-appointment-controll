@extends('Administración.dashboard')

{{--Section para poder trabajar con layout --}}

@section('content')

    <link rel="stylesheet" href="{{ asset('CSS/administracion/inventario.css')}}">
    <link rel="stylesheet" href="{{ asset('CSS/administracion/tables.css')}}">

    <!--Titulo de la vista -->
    <div class="container-fluid" style="margin-top: 5px;">
        <div class="title">
            <h2 style="color: black;">Agregar un Departamento</h2>
        </div>
    </div>
    <!-- Contenido de la vista -->
    <div class="contentInventario">
        <form method="get" id="form_carreras">
            @csrf
            <div class="opcionesInventario">

                <div class="col">
                    <p>Tipo de Departamento</p>
                    <input type="text" name="" class="form-control" id="" placeholder="Ingrese el tipo de departamento" style="width: 400px; padding: 10px;">
                </div>

                <div class="buttonContainer">
                    <input type="submit" value="Registrar Departamento" class="btn-success" id="submit">
                </div>

            </div>
        </form>
    </div>
@endsection
