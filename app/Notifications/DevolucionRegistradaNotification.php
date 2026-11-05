<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Devolucion;
use Illuminate\Support\Collection;

class DevolucionRegistradaNotification extends Notification
{
    use Queueable;

    public function __construct(public Devolucion $devolucion, public Collection $detalles)
    {
        $this->afterCommit();
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    private function totalMultas(): float
    {
        return (float) $this->detalles->flatMap->multas->sum('Monto');
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Devolución registrada')
            ->greeting("Hola {$notifiable->Nombres}")
            ->line('Recibimos los siguientes ejemplares:');

        foreach ($this->detalles as $d) {
            $mail->line("• {$d->ejemplar->libro->Titulo}: {$d->EstadoEntrega}");
        }

        if ($this->totalMultas() > 0) {
            $mail->line('Se generó una multa pendiente de $' . number_format($this->totalMultas(), 2)
                . '. No podrás pedir nuevos préstamos hasta pagarla.');
        }

        return $mail;
    }

    public function toArray($notifiable): array
    {
        $multa = $this->totalMultas();

        return [
            'tipo'    => 'devolucion',
            'mensaje' => 'Devolución registrada (' . $this->detalles->count() . ' ejemplar(es))'
                . ($multa > 0 ? '. Multa pendiente: $' . number_format($multa, 2) : '.'),
            'url'     => null,
        ];
    }
}
