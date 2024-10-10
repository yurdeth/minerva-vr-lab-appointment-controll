@extends('administration.dashboard')
@section('title', 'Inventario')

@section('content_header')
    <h1>Notificaciones</h1>
@stop

@section('content')
    <style>
        .button-container {
            margin-top: 20px;
            display: flex;
            gap: 24px;
            justify-content: center;
        }

        .custom-btn {
            background-color: #3498db;
            color: white;
            padding: 15px 24px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: background-color 0.3s ease;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .custom-btn:hover {
            background-color: #2980b9;
        }

        .custom-btn i {
            font-size: 18px;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('CSS/administracion/tables.css')}}">
    <link rel="stylesheet" href="{{ asset('CSS/administracion/responsiveInventory.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <div class="button-container">
        <a class="custom-btn btn-access-key" href="{{ route('solicitud-clave-default') }}"
           style="text-decoration: none;">
            <i class="fa-solid fa-key"></i> Solicitud de clave de acceso
        </a>

        <a class="custom-btn btn-access-key" href="{{ route('solicitud-recuperar-clave') }}"
           style="text-decoration: none;">
            <i class="fa-solid fa-unlock-alt"></i> Solicitudes de recuperación de contraseña
        </a>

        <a class="custom-btn btn-access-key" href="{{ route('solicitud-recuperar-clave') }}"
           style="text-decoration: none;">
            <i class="fa-solid fa-random"></i> Otras solicitudes
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive" style="height: 100%; width: 100%;">
            <table class="table table-bordered text-center" id="notificationsTable"
                   style="width: 100%; margin-bottom: 0;">
                <thead class="table-avatar">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Correo del solicitante</th>
                    <th scope="col">Tipo de solicitud</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody id=""></tbody>
            </table>
        </div>

        <!-- Contenedor para los botones de paginación -->
        <div id="pagination" class="pagination-container">
            <button id="prevPage" class="btn-arrow" disabled>
                <i class="fas fa-chevron-left"></i>
            </button>
            <span id="currentPage" class="page-number">1</span>
            <button id="nextPage" class="btn-arrow">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

    </div>

    <script src="{{ asset('js/notifications/showAllNotifications.js') }}" type="module"></script>

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('CSS/administracion.css')}}">
@stop
