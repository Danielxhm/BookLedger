<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Prestamo;
use App\Models\User;
use App\Notifications\PrestamoVencidoNotification;
use App\Notifications\RecordatorioVencimientoNotification;
use App\Notifications\ResumenVencidosNotification;
use Illuminate\Support\Facades\Notification;

#[Signature('prestamos:procesar-vencimientos')]
#[Description('Command description')]
class ProcesarVencimientos extends Command
{
    protected $signature = 'prestamos:procesar-vencimientos';
    protected $description = 'Avisa de préstamos por vencer y marca como vencidos los atrasados';

    public function handle(): int
    {
        // 1. Recordatorio: préstamos que vencen justo dentro de N días
        $porVencer = Prestamo::with('socio', 'detalles.ejemplar.libro')
            ->where('EstadoPrestamo', 'Activo')
            ->whereDate('FechaLimite_Devolucion', today()->addDays(config('biblioteca.dias_aviso_vencimiento')))
            ->get();

        foreach ($porVencer as $p) {
            $p->socio->notify(new RecordatorioVencimientoNotification($p));
        }

        // 2. Vencidos: activos cuya fecha límite ya pasó
        $vencidos = Prestamo::with('socio', 'detalles.ejemplar.libro')
            ->where('EstadoPrestamo', 'Activo')
            ->whereDate('FechaLimite_Devolucion', '<', today())
            ->get();

        foreach ($vencidos as $p) {
            $p->update(['EstadoPrestamo' => 'Vencido']);
            $p->socio->notify(new PrestamoVencidoNotification($p));
        }

        if ($vencidos->isNotEmpty()) {
            Notification::send(
                User::role(['Administrador', 'Bibliotecario'])->get(),
                new ResumenVencidosNotification($vencidos->count())
            );
        }

        $this->info("Recordatorios: {$porVencer->count()} | Marcados vencidos: {$vencidos->count()}");
        return self::SUCCESS;
    }
}
