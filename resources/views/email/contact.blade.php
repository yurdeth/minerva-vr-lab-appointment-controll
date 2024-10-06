<!DOCTYPE html>
<html>
<head>
    <title>Nuevo mensaje de contacto</title>
</head>
<body>
<h1>Has recibido un mensaje de contacto</h1>
<p>Nombre: {{ $details['name'] }}</p>
<p>Email: {{ $details['email'] }}</p>
<p>Mensaje: {{ $details['message'] }}</p>
</body>
</html>
