<?php

namespace App\Services;

use App\Models\Ejemplar;
use App\Models\Prestamo;
use App\Models\Reserva;
use App\Models\Socio;
use App\Models\User;
use App\Notifications\PrestamoRegistradoNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;

class PrestamoService
{
    public function registrar(int $codigoSocio, array $codigosEjemplar, int $codigoBibliotecario): Prestamo
    {
        $prestamo = DB::transaction(function () use ($codigoSocio, $codigosEjemplar, $codigoBibliotecario) {
            // Bloquear al socio serializa préstamos simultáneos del mismo socio
            $socio = Socio::lockForUpdate()->findOrFail($codigoSocio);

            if ($socio->EstadoSocio !== 'Activo') {
                $this->fallar('Codigo_Socio', "El socio está {$socio->EstadoSocio} y no puede recibir préstamos.");
            }
            if ($socio->multasPendientes()->exists()) {
                $this->fallar('Codigo_Socio', 'El socio tiene multas pendientes de pago.');
            }

            $max = config('biblioteca.max_ejemplares_socio');
            if ($socio->ejemplaresEnPoder() + count($codigosEjemplar) > $max) {
                $this->fallar('ejemplares', "El socio no puede tener más de {$max} ejemplares a la vez.");
            }

            // Bloquear los ejemplares evita que dos bibliotecarios presten el mismo
            $ejemplares = Ejemplar::whereIn('Codigo_Ejemplar', $codigosEjemplar)->lockForUpdate()->get();

            if ($ejemplares->count() !== count($codigosEjemplar)) {
                $this->fallar('ejemplares', 'Alguno de los ejemplares no existe.');
            }
            if ($ejemplares->contains(fn($e) => $e->Estado_Ejemplar !== 'Disponible')) {
                $this->fallar('ejemplares', 'Alguno de los ejemplares ya no está disponible.');
            }

            $prestamo = Prestamo::create([
                'Codigo_Socio'           => $socio->Codigo_Socio,
                'Codigo_Bibliotecario'   => $codigoBibliotecario,
                'EstadoPrestamo'         => 'Activo',
                'FechaPrestamo'          => today(),
                'FechaLimite_Devolucion' => today()->addDays(config('biblioteca.dias_prestamo')),
            ]);

            foreach ($ejemplares as $ejemplar) {
                $prestamo->detalles()->create(['Codigo_Ejemplar' => $ejemplar->Codigo_Ejemplar]);
                $ejemplar->update(['Estado_Ejemplar' => 'Prestado']);
            }

            // Si el socio tenía reserva de estos libros, queda atendida
            Reserva::where('Codigo_Socio', $socio->Codigo_Socio)
                ->whereIn('Codigo_ISBN', $ejemplares->pluck('Codigo_ISBN'))
                ->where('EstadoReserva', 'Pendiente')
                ->update(['EstadoReserva' => 'Atendida']);

            return $prestamo;
        });

        // Ya confirmado el commit: encolar la notificación
        $prestamo->load('socio', 'detalles.ejemplar.libro');
        $prestamo->socio->notify(new PrestamoRegistradoNotification($prestamo));

        // Campanita del resto del personal (quien registró ya lo sabe)
        $userId = Auth::id();
        $staff = User::role(['Administrador', 'Bibliotecario'])->where('id', '!=', $userId)->get();
        Notification::send($staff, new PrestamoRegistradoNotification($prestamo));

        return $prestamo;
    }

    private function fallar(string $campo, string $mensaje): never
    {
        throw ValidationException::withMessages([$campo => $mensaje]);
    }
}
