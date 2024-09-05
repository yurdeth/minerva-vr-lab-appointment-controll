{{--@extends('adminlte::page')--}}

@extends('layouts.layout')
@section('title', 'Inventario')

@section('content_header')
    <h1>Datos de inventario</h1>
@stop

@section('content')
    @vite(['resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <div class="container-xl" style="margin-top: 70px; margin-bottom: 70px;">
        <a href="{{route("inventario")}}" class="btn btn-success position-relative">
            <i class="fa-solid fa-vr-cardboard"></i>
            <span class="tooltip-text">Registrar Inventario</span>
        </a>
    </div>

    <div class="container-xl" style="margin-top: 70px; margin-bottom: 70px;">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h2 class="text-center">Tu inventario</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="height: 100%; width: 100%;">
                            <table class="table table-bordered text-center" style="width: 100%; margin-bottom: 0;" id="inventoryTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Número de sala</th>
                                        <th scope="col">Tipo de recurso</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Número de activo fijo</th>
                                        {{--<th scope="col">Número de activo fijo</th>--}}
                                        {{--<th scope="col">Computadora</th>--}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module" src="{{asset("js/getResponsePromise.js")}}"></script>
    <script type="module" src="{{ asset("js/getInventory.js") }}"></script>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('CSS/administracion.css')}}">
@stop
