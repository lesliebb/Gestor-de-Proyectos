<?php

namespace App\Mail;

use App\Models\SolicitudEquipo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SolicitudEquipoRespuesta extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SolicitudEquipo $solicitud,
        public bool $aceptada
    ) {
    }

    public function envelope(): Envelope
    {
        $estado = $this->aceptada ? 'aceptada' : 'rechazada';
        return new Envelope(
            subject: "Tu solicitud para unirte al equipo ha sido {$estado}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.solicitud-equipo-respuesta',
        );
    }
}
