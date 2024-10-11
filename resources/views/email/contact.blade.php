<!-- <!DOCTYPE html>
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
</html> -->
<!DOCTYPE html>
<html>
<head>
    <title>{{ $details['subject'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #4a4a4a;
            text-align: center;
            margin-bottom: 20px;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            margin: auto;
        }
        p {
            color: #333;
            line-height: 1.6;
            margin: 10px 0;
        }
    </style>
    </head>
    <body>
        <h1>Has recibido un mensaje de contacto</h1>
        <p><strong>De:<strong> {{ $details['name'] }}</p>
        <p><strong>Para:<strong> {{ $details['email'] }}</p>
        <p><strong>Mensaje:<strong> {{ $details['message'] }}</p>
    </body>
</html>