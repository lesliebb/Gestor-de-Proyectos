<?php

namespace App\Listeners;

use App\Events\SolicitudEquipoAceptada;
use App\Mail\SolicitudEquipoRespuesta;
use Illuminate\Support\Facades\Mail;

class EnviarEmailSolicitudAceptada
{
    public function handle(SolicitudEquipoAceptada $event): void
    {
        $participante = $event->solicitud->participante;
        
        if ($participante && $participante->user) {
            Mail::to($participante->user->email)->send(
                new SolicitudEquipoRespuesta($event->solicitud, true)
            );
        }
    }
}
