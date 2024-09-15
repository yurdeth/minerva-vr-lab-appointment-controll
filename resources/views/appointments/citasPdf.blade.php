<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <div class="table-responsive" style="height: 100%; width: 100%;">
        <table class="table table-bordered text-center" style="width: 100%; margin-bottom: 0;">
            <thead class="table-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">ID</th>
                <th scope="col">Encargado</th>
                <th scope="col">Departamento</th>
                <th scope="col">Carrera</th>
                <th scope="col">Fecha</th>
                <th scope="col">Hora</th>
                <th scope="col">Participantes</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($appointments as $appointment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $appointment->id }}</td>
                        <td>{{ $appointment->name }}</td>
                        <td>{{ $appointment->department_name }}</td>
                        <td>{{ $appointment->career_name }}</td>
                        <td>{{ $appointment->date }}</td>
                        <td>{{ $appointment->time }}</td>
                        <td>{{ $appointment->number_of_assistants }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
