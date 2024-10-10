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

                <form class="" method="post" action="{{ route("enviar-solicitud") }}">
                    @csrf

                    <div class="form-group position-relative p-2">
                        <input type="email" class="form-control" id="email" name="from"
                               placeholder="Ingresa tu correo electrónico" style="padding-left: 30px;" required>
                        <i class="fas fa-envelope position-absolute"
                           style="top: 50%; transform: translateY(-50%); left: 10px; padding-left: 5px;"></i>
                    </div>

                    <div class="form-group position-relative p-2">
                        <i class="fas fa-pencil-alt position-absolute" style="top: 50%; transform: translateY(-50%); left: 10px; padding-left: 5px;"></i>
                        <select class="form-select" id="tipo" name="type_id" style="padding-left: 30px;" required>
                            <option value="" selected disabled>Selecciona una opción</option>
                            <option value="1">Recuperación de contraseña</option>
                            <option value="2">Solicitar contraseña inicial</option>
                            <option value="3">Otro</option>
                        </select>
                    </div>

                    <div id="other-option" class="form-group position-relative p-2"></div>

                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-success text-white m-2 form-control" id="submitButton">
                            Enviar
                        </button>

                        <button type="button" class="btn btn-danger text-white m-2 form-control" id="cancelButton">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <br>
    </div>

    <script type="module" src="{{ asset('js/contactAdmin.js') }}"></script>
    <script type="module" src="{{asset("js/utils/alert.js")}}"></script>
    <script type="module" src="{{asset("js/utils/api.js")}}"></script>

    <br><br>

@endsection
