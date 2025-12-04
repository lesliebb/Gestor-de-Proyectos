<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #4CAF50; color: white; padding: 20px; border-radius: 5px; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
        .button { display: inline-block; background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .event-details { background-color: white; padding: 15px; border-left: 4px solid #4CAF50; margin: 15px 0; }
        strong { color: #2c5f2d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Se Te Ha Asignado un Evento</h1>
        </div>

        <div class="content">
            <p>Hola <strong>{{ $juez->name }}</strong>,</p>

            <p>¡Felicidades! Has sido asignado como juez para el siguiente evento:</p>

            <div class="event-details">
                <h2 style="margin-top: 0; color: #2c5f2d;">{{ $evento->nombre }}</h2>
                
                <p><strong>Descripción:</strong></p>
                <p>{{ $evento->descripcion }}</p>

                <p><strong>Fechas:</strong></p>
                <ul>
                    <li><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y H:i') }}</li>
                    <li><strong>Cierre:</strong> {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y H:i') }}</li>
                </ul>
            </div>

            <p>Accede a tu panel de control para ver los detalles completos:</p>

            <div style="text-align: center;">
                <a href="{{ route('juez.dashboard') }}" class="button">Ir al Dashboard</a>
            </div>

            <p>Saludos,<br>
            <strong>{{ config('app.name') }}</strong></p>
        </div>

        <div class="footer">
            <p>Este es un correo automático, por favor no responder a este mensaje.</p>
        </div>
    </div>
</body>
</html>
