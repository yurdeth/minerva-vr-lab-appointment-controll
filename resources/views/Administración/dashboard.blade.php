<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                    <a href="{{ route("inicio") }}" id=1>
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
                    <a href="#" onclick="loadView('{{ route('usuarios') }}', 'usuarios')">
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
                    <a href="#">
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
        </div>
        <br><br>
        <!-- Fin del Navbar Horizontal -->
        <!------------------------------------------------------------------------------------------------------------->

         <!-- Contenidos del Dashboard -->
        <div class="contenido">
            <div class="datos">
                <!-- Definimos una variable para el mensaje a mostrar -->
                @php
                    $mensaje = '';
                @endphp

                <!-- Verificamos si la variable mensaje no está vacía -->
                @if(!empty($mensaje))
                <!-- Si mensaje tiene contenido, se mostramos un encabezado con dicho mensaje -->
                    <h1>{{ $mensaje }}</h1>
                    <hr>
                @else
                 <!-- Si mensaje está vacío mostramos un mensaje de bienvenida por defecto -->
                    <h1>Bienvenido</h1>
                    <hr>
                @endif

                <br>
                <!-- Este div se usa para cargar las vistas que traemos al dashboard -->
                <div id="content"></div>
                </div>

        </div>

        <script>
            // Función para mostrar un mensaje basado en la opción seleccionada
            function mostrarMensaje(opcion) {
                let mensaje = '';
                switch(opcion) {
                    case 'carreras':
                        mensaje = 'Bienvenido a Carreras';
                        break;
                    case 'usuarios':
                        mensaje = 'Usuarios Minerva VR Web';
                        break;
                    case 'citas':
                        mensaje = 'Bienvenido a Citas';
                        break;
                    case 'inventario':
                        mensaje = 'Bienvenido a Inventario';
                        break;
                    default:
                        mensaje = 'Bienvenido';
                }
                // Actualizamos el texto del encabezado h1 dentro del div  con la clase datos
                document.querySelector('.datos h1').innerText = mensaje;
                }

                //Esta Función se encarga de traer una vista y mostrar un mensaje basado en la opción
                function loadView(url, opcion) {
                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            // Inserta el contenido HTML recibido en el div con id 'content'
                            document.getElementById('content').innerHTML = html;
                            // Muestra el mensaje correspondiente a la opción seleccionada
                            mostrarMensaje(opcion);
                        })
                        //Manejamos posibles excepciones que puedan ocurrir durante la ejecución de la función
                        .catch(error => console.error('Error loading view:', error));
                    }
        </script>

    </section>

</body>
</html>
