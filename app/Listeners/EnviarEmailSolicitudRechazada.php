<?php

namespace App\Listeners;

use App\Events\SolicitudEquipoRechazada;
use App\Mail\SolicitudEquipoRespuesta;
use Illuminate\Support\Facades\Mail;

class EnviarEmailSolicitudRechazada
{
    public function handle(SolicitudEquipoRechazada $event): void
    {
        $participante = $event->solicitud->participante;
        
        if ($participante && $participante->user) {
            Mail::to($participante->user->email)->send(
                new SolicitudEquipoRespuesta($event->solicitud, false)
            );
        }
    }
}
