@extends('adminlte::page')
<link rel="icon" href="{{ asset('IMG/LogoUES.png') }}">

@section('title', 'Dashboard')

@section('content_header')
    <h1>Registro de citas</h1>
@stop

@section('content')
    <div class="container-xl" style="margin-top: 70px; margin-bottom: 70px;">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h2 class="text-center">Todas las Citas Registradas</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="height: 100%; width: 100%;">
                            <table class="table table-bordered text-center" style="width: 100%; margin-bottom: 0;">
                                <thead class="table-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Encargado</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Carrera</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Hora</th>
                                    <th scope="col">Participantes</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Acciones</th>
                                    <th scope="col">Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <th scope="row">1</th>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td>@mdo</td>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td>Mark</td>
                                    <td><button type="button" class="btn btn-primary">Agregar</button>
                                        <button type="button" class="btn btn-success">Editar</button>
                                        <button type="button" class="btn btn-danger">Eliminar</button></td>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('CSS/administracion.css')}}">
@stop
