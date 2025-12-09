<?php

namespace App\Events;

use App\Models\InvitacionEquipo;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvitacionEquipoEnviada
{
    use Dispatchable, SerializesModels;

    public $invitacion;

    public function __construct(InvitacionEquipo $invitacion)
    {
        $this->invitacion = $invitacion;
    }
}
