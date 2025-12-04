<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\SolicitudEquipoAceptada;
use App\Events\SolicitudEquipoRechazada;
use App\Listeners\EnviarEmailSolicitudAceptada;
use App\Listeners\EnviarEmailSolicitudRechazada;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SolicitudEquipoAceptada::class => [
            EnviarEmailSolicitudAceptada::class,
        ],
        SolicitudEquipoRechazada::class => [
            EnviarEmailSolicitudRechazada::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
