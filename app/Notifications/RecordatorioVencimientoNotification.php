<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Prestamo;

class RecordatorioVencimientoNotification extends Notification
{
    use Queueable;

    public function __construct(public Prestamo $prestamo) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $limite = $this->prestamo->FechaLimite_Devolucion->format('d/m/Y');

        $mail = (new MailMessage)
            ->subject("Tu préstamo #{$this->prestamo->Codigo_Prestamo} vence pronto")
            ->greeting("Hola {$notifiable->Nombres}")
            ->line("Recuerda devolver estos libros antes del {$limite}:");

        foreach ($this->prestamo->detalles->whereNull('Codigo_Devolucion') as $d) {
            $mail->line("• {$d->ejemplar->libro->Titulo}");
        }

        return $mail->line('Pasada la fecha se genera una multa de $'
            . number_format(config('biblioteca.multa_por_dia'), 2) . ' por día y por ejemplar.');
    }

    public function toArray($notifiable): array
    {
        return [
            'tipo'    => 'recordatorio',
            'mensaje' => "Tu préstamo #{$this->prestamo->Codigo_Prestamo} vence el "
                . $this->prestamo->FechaLimite_Devolucion->format('d/m/Y') . '.',
            'url'     => null,
        ];
    }
}
