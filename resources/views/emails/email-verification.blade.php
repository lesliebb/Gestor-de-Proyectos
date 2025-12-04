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
        strong { color: #2c5f2d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verifica tu Correo Electrónico</h1>
        </div>

        <div class="content">
            <p>Hola <strong>{{ $user->name }}</strong>,</p>

            <p>Gracias por registrarte en <strong>GesPro Académico</strong>. Para completar tu registro, debes verificar tu correo electrónico.</p>

            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button">Verificar Correo</a>
            </div>

            <p style="color: #999; font-size: 14px;">Si el botón anterior no funciona, copia y pega este enlace en tu navegador:<br>
            <code>{{ $verificationUrl }}</code></p>

            <p style="color: #999;">Si no realizaste este registro, puedes ignorar este correo.</p>

            <p>Saludos,<br>
            <strong>{{ config('app.name') }}</strong></p>
        </div>

        <div class="footer">
            <p>Este es un correo automático, por favor no responder a este mensaje.</p>
        </div>
    </div>
</body>
</html>
