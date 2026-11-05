<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResumenVencidosNotification extends Notification
{
    use Queueable;

    public function __construct(public int $total) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'tipo'    => 'vencidos',
            'mensaje' => "{$this->total} préstamo(s) pasaron a Vencido hoy.",
            'url'     => route('prestamos.index'),
        ];
    }
}
