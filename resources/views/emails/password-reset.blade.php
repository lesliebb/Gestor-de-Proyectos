<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #2196F3; color: white; padding: 20px; border-radius: 5px; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
        .button { display: inline-block; background-color: #2196F3; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .warning { background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 15px 0; }
        strong { color: #1565c0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Recupera tu Contraseña</h1>
        </div>

        <div class="content">
            <p>Hola <strong>{{ $userName }}</strong>,</p>

            <p>Recibimos una solicitud para recuperar tu contraseña. Haz clic en el botón de abajo para establecer una nueva contraseña.</p>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Recuperar Contraseña</a>
            </div>

            <p style="color: #999; font-size: 14px;">Si el botón anterior no funciona, copia y pega este enlace en tu navegador:<br>
            <code>{{ $resetUrl }}</code></p>

            <div class="warning">
                <strong>⏱️ Importante:</strong> Este enlace expirará en 60 minutos.
            </div>

            <p style="color: #999;">Si no solicitaste esto, puedes ignorar este correo de forma segura.</p>

            <p>Saludos,<br>
            <strong>{{ config('app.name') }}</strong></p>
        </div>

        <div class="footer">
            <p>Este es un correo automático, por favor no responder a este mensaje.</p>
        </div>
    </div>
</body>
</html>
