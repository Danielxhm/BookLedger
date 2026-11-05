<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reserva;


class NuevaReservaNotification extends Notification
{
    use Queueable;

    protected Reserva $reserva;

    public function __construct(Reserva $reserva)
    {
        $this->reserva = $reserva;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'mensaje' => "{$this->reserva->socio->Nombres} {$this->reserva->socio->Apellidos} reservó \"{$this->reserva->libro->Titulo}\"",
            'reserva_id' => $this->reserva->Codigo_Reserva,
        ];
    }
}
