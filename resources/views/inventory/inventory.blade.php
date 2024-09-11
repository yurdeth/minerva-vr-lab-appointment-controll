@extends('Administración.dashboard')
@section('title', 'Inventario')

@section('content_header')
    <h1>Datos de inventario</h1>
@stop

@section('content')

    <style>
        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px; /* Aumenté el padding para hacer más evidente */
            max-width: 300px; /* Ajusta el ancho máximo según necesites */
            margin: 8px auto; /* Esto centrará el contenedor */
        }
        /* .pagination-container {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 5px;
            width: max-content;
            margin-top: 8px;
        } */

        .btn-arrow {
            background-color: transparent;
            border: none;
            font-size: 18px;
            color: #ff4a4a;
            padding: 5px 10px;
            cursor: pointer;
        }

        .btn-arrow:disabled {
            color: #ccc;
            cursor: not-allowed;
        }

        .page-number {
            font-size: 18px;
            margin: 0 15px;
            font-weight: bold;
        }

        .custom-flex {
            display: flex;
            justify-content: space-between;
            align-items: center; /* Alinea verticalmente ambos elementos */
        }
    </style>

<link rel="stylesheet" href="{{ asset('CSS/administracion/tables.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <div class="container-xl custom-flex" style="margin-bottom: 20px;">
        <div>
            <a href="{{route('registrar-inventario')}}" class="btnInventario" style="margin-top: 1px;">
                <i class="fa-solid fa-vr-cardboard"></i>
                <span class="tooltip-text">Registrar Inventario</span>
            </a>
        </div>

        <div>
            <form action="" id="searchForm" style="margin-top: 24px;">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                    <button type="submit" class="btn btn-success">Buscar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive" style="height: 100%; width: 100%;">
                            <table class="table table-bordered text-center" style="width: 100%; margin-bottom: 0;" id="inventoryTable">
                                <thead class="table-avatar">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Número de sala</th>
                                        <th scope="col">Tipo de recurso</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Número de activo fijo</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr></tr>
                                </tbody>
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
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchForm = document.getElementById('searchForm');
            const searchInput = document.getElementById('searchInput');
            const inventoryTable = document.getElementById('inventoryTable'); // Corrected ID

            searchForm.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent form submission
            });

            searchInput.addEventListener('input', function () {
                const searchTerm = searchInput.value.trim().toLowerCase();

                // Filter table rows based on search term
                Array.from(inventoryTable.getElementsByTagName('tr')).forEach(function (row, index) {
                    if (index === 0) return; // Skip header row

                    const cells = row.getElementsByTagName('td');
                    let rowMatchesSearch = false;

                    Array.from(cells).forEach(function (cell) {
                        const cellText = cell.textContent.toLowerCase();

                        if (cellText.includes(searchTerm)) {
                            rowMatchesSearch = true;
                        }
                    });

                    row.style.display = rowMatchesSearch ? '' : 'none';
                });
            });
        });
    </script>

    <script type="module" src="{{asset("js/utils/api.js")}}"></script>
    <script type="module" src="{{ asset("js/getInventory.js") }}"></script>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('CSS/administracion.css')}}">
@stop
