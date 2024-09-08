@extends('Administración.dashboard')

{{--Section para poder trabajar con layout --}}

@section('content')
<link rel="stylesheet" href="{{ asset('CSS/administracion/inventario.css')}}">
<!--Titulo de la vista -->
    <div class="container-fluid">
        <div class="title">
            <h2>Registro de Inventario de Equipos de VR</h2>
        </div>
    </div>
<!-- Contenido de la vista -->
    <div class="contentInventario">
        <form action="{{ route("registro-inventario") }}" method="get" id="form_inventario">
        @csrf
            <div class="opcionesInventario">

                <div class="col">
                    <p>Número de sala</p>
                        <select name="room" class="form-select" id="room">
                            <option value="0">Seleccionar sala</option>
                        </select>
                </div>

                <div class="col">
                <p>Número de Activo Fijo</p>
                   <input type="text" class="ActivoFijo" placeholder="112333">
                </div>

                <div class="col">
                    <p>Tipo de recurso</p>
                        <select name="resource_type" class="form-select" id="resource_type">
                            <option value="0">Seleccionar tipo de recurso</option>
                        </select>
                </div>

                <div class="col">
                    <p>Tipo de estado</p>
                        <select name="status" class="form-select status" id="status">
                            <option value="0">Seleccionar estado</option>
                            {{--<option value="1">Buen estado</option>
                            <option value="1">Mal estado</option>
                            <option value="1">Reparación</option>--}}
                        </select>
                </div>

                <div class="col">
                    {{--<p>Numero de recurso</p>
                            <select name="pc" class="form-select">
                                <option value="0">Seleccionar recurso</option>
                                <option value="1">Computadora 1</option>
                                <option value="1">Computadora 1</option>
                                <option value="1">Computadora 1</option>
                                <option value="2">Seleccion desde db</option>
                                <option value="2">Seleccion desde db</option>
                                <option value="2">Seleccion desde db</option>
                            </select>--}}
                </div>

                <div class="buttonContainer">
                    <input type="submit" value="Registrar inventario" class="btn-success"  id="submit">
                </div>

            </div>
        </form>
    </div>

    <script type="module" src="{{asset("js/getResponsePromise.js")}}"></script>
    <script type="module" src="{{ asset("js/makeOptions.js") }}"></script>
@endsection
