<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Minerva VR Web</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/choices.js/1.1.6/styles/css/choices.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    @vite(['resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('CSS/home.css')}}">
    <link rel="stylesheet" href="{{ asset('CSS/floating-span.css')}}">
    <link rel="icon" href="{{ asset('IMG/LogoUES.png') }}">
</head>
<body>

<!--Navbar de la pagina-->
<div id="app">
    <nav class="navbar navbar-expand-md shadow-sm">
        <div class="container-fluid">
            <div class="logo_title">
                <a class="navbar-brand text-light" href="{{ route("inicio") }}">
                    <img class="logo" src="{{ asset('IMG/Logo.png') }}" alt="Logo">
                    Minerva VR Lab FMO
                </a>
            </div>

            <button class="navbar-toggler text-warning bg-white" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    @auth
                        <button><a class="nav-link" href="{{ route("HomeVR") }}">Inicio</a></button>
                        <button class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                               aria-expanded="false">
                                Citas
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route("agendar") }}">Agendar Citas</a></li>
                                <li><a class="dropdown-item" href="{{ route("appointments") }}">Mis citas</a></li>
                                @if( Auth::user()->roles_id == 1 )
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="{{route("dashboard")}}">Calendario de citas </a>
                                    </li>
                                @endif
                            </ul>
                        </button>
                        @if( Auth::user()->roles_id == 1 )
                            <button><a class="nav-link" href="{{ route("dashboard") }}">Panel de Administración</a>
                            </button>
                        @endif
                        <button><a class="nav-link" href="{{ route("logout") }}">Cerrar Sesión</a></button>
                        <button>
                            <a class="nav-link" href="{{ route('usuarios-editar', ['id' => Auth::user()->id]) }}">
                                <span class="navbar-text" style="color: white">{{ Auth::user()->name }}</span>
                            </a>
                        </button>
                    @else
                        <button><a class="nav-link" href="{{ route('iniciar_sesion') }}">Iniciar sesión</a></button>
                        <button><a class="nav-link" href="{{ route('registrarse') }}">Registrarse</a></button>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</div>
<!--Fin del Navbar de la pagina-->

{{-- Contenido de la pagina --}}
@yield('content')

<!--Footer de la pagina-->
<section>
    <footer>
        <div class="foo">
            <div>
                <h4>Información</h4>
                <ul>
                    <li>Quiénes somos</li>
                    <li>Servicios</li>
                    <li>Contacto</li>
                </ul>
                <br>
                <hr>
            </div>

            <div>
                <h4 style="text-align: center;">Horarios de Atención</h4>
                <p style="text-align: center;">Lunes-Viernes: <br>8.00 a.m. - 4.00 p.m.</p>
                <br>
                <h4 style="text-align: center;">Minerva VR Lab</h4>

                <div style="text-align: center;  font-size: 30px;">
                    <i class="fa-brands fa-facebook"></i>
                    <i class="fa-brands fa-instagram"></i>
                </div>
            </div>

            <div>
                <h4>Enlaces Rapidos</h4>
                <ul>
                    <li>Mi cuenta</li>
                    <li>Ubicación</li>
                    <li>Mis citas</li>
                </ul>
                <br>
                <hr>
            </div>
        </div>

        <div class="text-center p-3" style="background-color: #74150b">
            <i class="fa-regular fa-copyright"></i> UNIVERSIDAD DE EL SALVADOR - FACULTAD MULTIDISCIPLINARIA <br>
            ORIENTAL
        </div>
    </footer>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/choices.js/1.1.6/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset("js/main.js")}}"></script>
<script src="{{asset("js/main.js")}}"></script>
</body>
</html>
