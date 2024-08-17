@extends('layouts.layout')

{{--Section para poder trabajar con layout --}}

@section('content')

    <div class="container-fluid" style="margin-top: 70px; margin-bottom: 70px; max-width: 950px;">
        <div class="row bg-white">
            <div class="col-12">
                <div class="row text-center">
                    <h2>Informe de inventario</h2>
                </div>
            </div>
            <div class="col-2"></div>
            <div class="col8">
                <div class="">
                    <form action="{{ route("registro-inventario") }}" method="get" id="form_inventario">
                        @csrf
                        <div class="row mb-5 d-flex justify-content-center align-items-center">
                            <div class="col-md-5 col-12">
                                <p>Número de sala</p>
                                <select name="room" class="form-select" id="room">
                                    <option value="0">Seleccionar sala</option>

                                </select>
                            </div>
                            <div class="col-md-1 col-12"></div>
                            <div class="col-md-5 col-12">
                            </div>
                        </div>
                        <div class="row mb-5 d-flex justify-content-center align-items-center">
                            <div class="col-md-5 col-12">
                                <p>Tipo de recurso</p>
                                <select name="resource_type" class="form-select" id="resource_type">
                                    <option value="0">Seleccionar tipo de recurso</option>
                                </select>
                            </div>
                            <div class="col-md-1 col-12"></div>
                            <div class="col-md-5 col-12">
                                <p>Tipo de estado</p>
                                <select name="status" class="form-select status" id="status">
                                    <option value="0">Seleccionar estado</option>
                                    {{--<option value="1">Buen estado</option>
                                    <option value="1">Mal estado</option>
                                    <option value="1">Reparación</option>--}}
                                </select>
                            </div>
                        </div>
                        <div class="row mb-5 d-flex justify-content-center align-items-center">
                            <div class="col-md-5 col-12">
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
                            <div class="col-md-1 col-12"></div>
                            <div class="col-md-5 col-12">
                                {{--<p>Tipo de estado</p>
                                <select name="status_nam" class="form-select status">
                                    <option value="0">Seleccionar estado</option>
                                    --}}{{--<option value="1">Buen estado</option>
                                    <option value="1">Mal estado</option>
                                    <option value="1">Reparación</option>--}}{{--
                                </select>--}}
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center align-items-center mb-3">
                            <div class="col-md-5 col-12">
                                {{--<div>
                                    <p>Número de activo fijo</p>
                                </div>
                                <div class="form-floating">
                                    <input type="number" name="numero_activo" class="form-control"
                                           placeholder="Número de activo fijo" id="floatingInput" min="0">
                                    <label for="floatingInput">Número de activo fijo</label>
                                </div>--}}
                            </div>
                            <div class="col-md-1 col-12"></div>
                            <div class="col-md-5 col-12">
                                {{--<p>Computadora</p>
                                <select name="pc_activo_fijo" class="form-select">
                                    <option value="0">Seleccionar computadora</option>
                                    <option value="1">Computadora 1</option>
                                    <option value="2">Seleccion desde db</option>
                                </select>--}}
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-md-4 col-12"></div>
                            <div class="col-md-4 col-12">
                                <input type="submit" value="Registrar inventario" class="btn text-white" style="width: 100%; background-color: #660D04;" id="submit">
                            </div>
                            <div class="col-md-4 col-12"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset("js/makeOptions.js") }}"></script>
@endsection
