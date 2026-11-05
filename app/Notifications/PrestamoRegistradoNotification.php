<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Prestamo;
use App\Models\Socio;



class PrestamoRegistradoNotification extends Notification
{
    use Queueable;

    public function __construct(public Prestamo $prestamo)
    {
        $this->afterCommit();
    }

    public function via($notifiable): array
    {
        return $notifiable instanceof Socio ? ['mail', 'database'] : ['database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject("Préstamo #{$this->prestamo->Codigo_Prestamo} registrado")
            ->greeting("Hola {$notifiable->Nombres}")
            ->line('Se registró un préstamo a tu nombre con los siguientes libros:');

        foreach ($this->prestamo->detalles as $d) {
            $mail->line("• {$d->ejemplar->libro->Titulo} (ejemplar #{$d->Codigo_Ejemplar})");
        }

        return $mail->line('Fecha límite de devolución: ' . $this->prestamo->FechaLimite_Devolucion->format('d/m/Y'))
            ->line('Si lo devuelves con retraso se generará una multa.');
    }

    public function toArray($notifiable): array
    {
        $p = $this->prestamo;

        if ($notifiable instanceof Socio) {
            return [
                'tipo'    => 'prestamo',
                'mensaje' => "Préstamo #{$p->Codigo_Prestamo} registrado. Devuélvelo antes del "
                    . $p->FechaLimite_Devolucion->format('d/m/Y') . '.',
                'url'     => null,
            ];
        }

        return [
            'tipo'    => 'prestamo',
            'mensaje' => "Préstamo #{$p->Codigo_Prestamo} a {$p->socio->Nombres} {$p->socio->Apellidos} ("
                . $p->detalles->count() . ' ejemplar(es)).',
            'url'     => route('prestamos.show', $p),
        ];
    }
}
