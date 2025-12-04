<?php

namespace App\Mail;

use App\Models\Evento;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JuezEventoAsignado extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $juez, public Evento $evento)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Se te ha asignado el evento: {$this->evento->nombre}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.juez-evento-asignado',
        );
    }
}
