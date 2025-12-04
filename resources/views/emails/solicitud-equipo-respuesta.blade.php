<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { padding: 20px; border-radius: 5px; text-align: center; }
        .header.aceptada { background-color: #4CAF50; color: white; }
        .header.rechazada { background-color: #f44336; color: white; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
        .button { display: inline-block; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .button.aceptada { background-color: #4CAF50; }
        .button.rechazada { background-color: #f44336; }
        .status-badge { display: inline-block; padding: 10px 15px; border-radius: 5px; font-weight: bold; margin: 10px 0; }
        .status-aceptada { background-color: #c8e6c9; color: #2e7d32; }
        .status-rechazada { background-color: #ffcdd2; color: #c62828; }
        strong { color: #1565c0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header {{ $aceptada ? 'aceptada' : 'rechazada' }}">
            <h1>Respuesta a Tu Solicitud de Equipo</h1>
        </div>

        <div class="content">
            <p>Hola <strong>{{ $solicitud->participante->user->name }}</strong>,</p>

            <p>Tu solicitud para unirte al equipo <strong>{{ $solicitud->equipo->nombre }}</strong> ha sido:</p>

            <div style="text-align: center;">
                <div class="status-badge {{ $aceptada ? 'status-aceptada' : 'status-rechazada' }}">
                    {{ $aceptada ? '✓ ACEPTADA' : '✗ RECHAZADA' }}
                </div>
            </div>

            @if($aceptada)
                <p style="color: #2e7d32; background-color: #e8f5e9; padding: 15px; border-radius: 5px;">
                    <strong>¡Felicidades!</strong> ¡Bienvenido al equipo! Ahora puedes colaborar con tus compañeros en los proyectos.
                </p>

                <div style="text-align: center;">
                    <a href="{{ route('participante.equipos.show', $solicitud->equipo) }}" class="button aceptada">Ver Equipo</a>
                </div>
            @else
                <p style="color: #c62828; background-color: #ffebee; padding: 15px; border-radius: 5px;">
                    Lamentablemente, tu solicitud fue rechazada. Puedes intentar unirte a otro equipo o crear el tuyo propio.
                </p>
            @endif

            <p>Saludos,<br>
            <strong>{{ config('app.name') }}</strong></p>
        </div>

        <div class="footer">
            <p>Este es un correo automático, por favor no responder a este mensaje.</p>
        </div>
    </div>
</body>
</html>
