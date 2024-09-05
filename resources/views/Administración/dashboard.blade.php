<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
          integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="{{ asset('CSS/administracion/dashboard.css')}}">
    <link rel="stylesheet" href="{{ asset('CSS/administracion/responsiveDashboard.css')}}">
</head>
<body>
<!-- New control panel design -->
<section>
    <!--Menu vertical-->
    <div class="menuVertical">
        <ul>
            <li>
                <div class="logo_container">
                    <img class="logo" src="{{ asset('IMG/Logo FMO.png') }}" alt="Logo">
                    <span class="logo_title"> Minerva VR Web</span>
                </div>
            </li>
            <li>
                <a href="{{ route("inicio") }}">
                    <span><i class="fa-solid fa-house"></i></span>
                    <span class="Opciones">Home</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa-solid fa-school"></i>
                    <span class="Opciones">Carreras</span>
                </a>
            </li>
            <li>
                <a href="{{route("usuarios-dashboard")}}">
                    <span><i class="fa-solid fa-user-group"></i></span>
                    <span class="Opciones">Usuarios</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span><i class="fa-solid fa-calendar-days"></i></span>
                    <span class="Opciones">Citas</span>
                </a>
            </li>
            <li>
                <a href="{{route("registro-inventario")}}">
                    <span><i class="fa-solid fa-vr-cardboard"></i></span>
                    <span class="Opciones">Inventario</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- Fin del Menu Vertical -->
    <!------------------------------------------------------------------------------------------------------------->

    <!-- Navbar Horizontal -->
    <div class="container">
        <div class="navHorizontal">
            <ul>
                <li>
                    <a href="#">
                        <span><i class="fa-solid fa-user"></i></span>
                        <span class="title">Admin</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
                        <span class="title">Salir</span>
                    </a>
                </li>
            </ul>
        </div>
{{--        Aqui iba el div de cierre de <div class="container">--}}
{{--        </div>--}}
        <br><br>
        <!-- Fin del Navbar Horizontal -->
        <!------------------------------------------------------------------------------------------------------------->

        <!-- Contenidos del Dashboard -->
        <div class="">
            <div class="">
{{--                <h1>Bienvenido</h1>--}}
                <hr>
                @yield('content_header')
                @yield('content')
            </div>
        </div>
    </>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/choices.js/1.1.6/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset("js/main.js")}}"></script>
{{--<script src="{{asset("js/test.js")}}"></script>--}}
</body>
</html>
