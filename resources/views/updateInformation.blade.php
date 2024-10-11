<!--Incluimos el header y footer del layout-->
@extends('layouts.layout')

{{--Section para poder trabajar con layout --}}
{{--@section('content')--}}

@section('content_header')
    <h1>Perfil</h1>
@stop

@section('content')
    @vite(['resources/js/app.js'])

    <style>
        .btn-primary {
            background-color: #007bff;
            color: #fff;
            padding: 8px 18px;
            cursor: pointer;
            border-radius: 3px;
            border: none #007bff;
            text-decoration: none;
            width: 100%;
            display: block;
            margin-bottom: 5px;
        }

        .btn-danger {
            background-color: #d33;
            color: white;
            border: none #f65e3f;
            padding: 8px 18px;
            cursor: pointer;
            border-radius: 3px;
            width: 100%;
            display: block;
            margin-bottom: 5px;
        }
    </style>

    <div class="container-xl" style="margin-top: 50px; margin-bottom: 70px;">

        <div class="row justify-content-center">

            <div class="col-md-6">
                <div class="card">

                    <div class="card-header">
                        <h2 class="text-center">Datos</h2>
                    </div>

                    <div class="card-body">

                        <form id="updateForm" method="POST">
                            <input type="hidden" value="{{ $randKey }}" id="rand">
                            @csrf
                            @method("POST")

                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="email" name="email" readonly>
                            </div>

                            <div class="form-group position-relative">
                                <label for="department" class="form-label">Departamento</label>
                                <select name="department" id="department" class="form-control" required>
                                    <option disabled>Seleccione un departamento</option>
                                </select>
                            </div>

                            <div class="form-group position-relative">
                                <label for="career" class="form-label">Carrera</label>
                                <select name="career" id="career" class="form-control" required>
                                    <option disabled>Carrera</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña: </label>
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="Ingrese su nueva contraseña">
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Repita su nueva contraseña: </label>
                                <input type="password" class="form-control" id="password_confirmation"
                                       name="password_confirmation" placeholder="Confirmar">
                            </div>
                        </form>
                        <div id="actionsButtons" class="row">
                            <button class="btn-primary" id="updateButton">Actualizar contraseña</button>
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>

    <script type="module" src="{{asset("js/utils/alert.js")}}"></script>
    <script type="module" src="{{asset("js/utils/api.js")}}"></script>
    <script src="{{ asset('js/loadDepartments.js') }}"></script>
    <script src="{{ asset('js/loadCareers.js') }}"></script>
    <script type="module" src="{{ asset('js/register.js') }}"></script>

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('CSS/administracion.css')}} ">
@stop
