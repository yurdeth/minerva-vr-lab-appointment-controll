<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Reporte de Inventario</title>
</head>
<body>

    <h4 class="text-center">Minerva RV Lab FMO</h4> <br>
    <h5 class="text-center">Reporte de Inventario</h5>

    <div class="table-responsive" style=" width: 100%;">
        <table class="table table-bordered text-center" style="width: 100%; margin-bottom: 0; border-collapse: collapse;">
            <thead class="table-secondary" style="background-color: #343a40; color: white;">
                <tr>
                    <th>Número de sala</th>
                    <th>Tipo de recurso</th>
                    <th>Estado</th>
                    <th>Número de activo fijo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resources as $resource)
                <tr>
                    <td style="padding: 10px; border: 1px solid #dee2e6;">{{ $resource->room->name }}</td>
                    <td style="padding: 10px; border: 1px solid #dee2e6;">{{ $resource->resourceType->resource_name }}</td>
                    <td style="padding: 10px; border: 1px solid #dee2e6;">
                        @if($resource->status['status'] == 'Mal estado')
                            <span style="color: #dc3545;">Mal estado</span>
                        @elseif($resource->status['status'] == 'Buen estado')
                            <span style="color: #28a745;">Buen estado</span>
                        @elseif($resource->status['status'] == 'En reparación')
                            <span style="color: #0B5ED7;">En reparación</span>
                        @endif
                    </td>
                    <td style="padding: 10px; border: 1px solid #dee2e6;">{{ $resource->fixed_asset_code }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


</body>
</html>
