<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reserva;
use App\Models\Prestamo;

class ReservaDisponibleNotification extends Notification
{
    use Queueable;

    public function __construct(public Prestamo $prestamo) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject("Tu préstamo #{$this->prestamo->Codigo_Prestamo} está vencido")
            ->greeting("Hola {$notifiable->Nombres}")
            ->line('Venció el plazo de devolución de:');

        foreach ($this->prestamo->detalles->whereNull('Codigo_Devolucion') as $d) {
            $mail->line("• {$d->ejemplar->libro->Titulo}");
        }

        return $mail->line('Cada día de retraso genera una multa de $'
            . number_format(config('biblioteca.multa_por_dia'), 2) . ' por ejemplar. Devuélvelos lo antes posible.');
    }

    public function toArray($notifiable): array
    {
        return [
            'tipo'    => 'vencido',
            'mensaje' => "Tu préstamo #{$this->prestamo->Codigo_Prestamo} está vencido. Se acumula multa por día.",
            'url'     => null,
        ];
    }

}
