<!--Incluimos el header y footer del layout-->
@extends('layouts.layout')

{{--Section para poder trabajar con layout --}}
@section('content')

<br>

    <div class="container-sm"
         style="margin-top: 70px; margin-bottom: 70px; border: 2px solid #660D04; border-radius: 4px; max-width: 950px; height: 400px;">

        <div class="row">

            <div class="col-6 d-flex align-items-center text-white p-5 text-center" style="background-color: #660D04; height: 396px;">
                <div class="mx-auto">
                    <img class="logo m-2" src="{{ asset('IMG/Logo FMO.png') }}" alt="Logo"
                         style="width: 100px; height: 120px;">
                    <h2>FACULTAD MULTIDISPLINARIA ORIENTAL</h2>
                </div>
            </div>

            <div class="col-6 bg-white p-5 text-center">
                <br>
                <h2>Iniciar sesión</h2>

                <form class="" method="post" action="{{ route("signin") }}">
                    @csrf

                    <div class="form-group position-relative p-2">
                        <input type="email" class="form-control" id="correo" name="email"
                               placeholder="Correo electrónico" style="padding-left: 30px;">
                        <i class="fas fa-envelope position-absolute"
                           style="top: 50%; transform: translateY(-50%); left: 10px; padding-left: 5px;"></i>
                    </div>

                    <div class="form-group position-relative p-2">
                        <input type="password" class="form-control" id="contrasena" name="password"
                               placeholder="Contraseña" style="padding-left: 30px;">
                        <i class="fas fa-key position-absolute"
                           style="top: 50%; transform: translateY(-50%); left: 10px; padding-left: 5px;"></i>
                    </div>

                    <button type="submit" class="btn text-white m-2 form-control" id="submitButton"
                            style="background-color: #660D04;">
                        Ingresar
                    </button>

                    <p class="mt-3"><strong>¿No tienes cuenta?</strong> <a href="{{ route('registrarse') }}"
                                                                           style="text-decoration: none; color: #660D04;">
                            <strong>Regístrate</strong> </a></p>
                </form>

            </div>

        </div>

        <br>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>

    <br><br>

@endsection
