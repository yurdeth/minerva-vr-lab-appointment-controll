<!--Incluimos el header y footer del layout-->
@extends('layouts.layout')

{{--Section para poder trabajar con layout --}}
@section('content')

    <br>

    <div class="container-sm"
         style="margin-top: 70px; margin-bottom: 70px; border: 2px solid #242525; border-radius: 4px; max-width: 950px; height: 400px;">

        <div class="row">

            <div class="col-6 d-flex align-items-center text-white p-5 text-center" id="contenedorImagen"
                 style=" height: 396px;">
                <div class="mx-auto">
                    <img class="logoInicio m-2" src="{{ asset('IMG/LogoLentes.png') }}" alt="Logo" id="logoInicio">
                    <h2>FACULTAD MULTIDISCIPLINARIA ORIENTAL</h2>
                </div>
            </div>

            <div class="col-6 bg-white p-5 text-center">
                <br>
                <h2>Contactar al Administrador</h2>

                <form class="" method="post" action="{{ route("signin") }}">
                    @csrf

                    <div class="form-group position-relative p-2">
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="Ingresa tu correo electrónico" style="padding-left: 30px;">
                        <i class="fas fa-envelope position-absolute"
                           style="top: 50%; transform: translateY(-50%); left: 10px; padding-left: 5px;"></i>
                    </div>

                    <div class="form-group position-relative p-2">
                        <input type="text" class="form-control" id="asunto" name="asunto"
                               placeholder="Asunto..." style="padding-left: 30px;">
                        <i class="fas fa-pencil-alt position-absolute"
                           style="top: 50%; transform: translateY(-50%); left: 10px; padding-left: 5px;"></i>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn text-white m-2 form-control" id="submitButton">
                            Enviar
                        </button>

                        <button type="submit" class="btn text-white m-2 form-control" id="submitButton">
                            Cancelar
                        </button>
                    </div>
                </form>

            </div>

        </div>

        <br>
    </div>

    <script type="module" src="{{ asset('js/login.js') }}"></script>
    <script type="module" src="{{asset("js/utils/alert.js")}}"></script>
    <script type="module" src="{{asset("js/utils/api.js")}}"></script>

    <br><br>

@endsection