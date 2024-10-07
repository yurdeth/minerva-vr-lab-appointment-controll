<!DOCTYPE html>
<html>
<head>
    <title>{{ $details['subject'] }}</title>
</head>
<body>
<h1>Has recibido un mensaje de contacto</h1>
<p>De: {{ $details['name'] }}</p>
<p>Para: {{ $details['email'] }}</p>
<p>Mensaje: {{ $details['message'] }}</p>
</body>
</html>
