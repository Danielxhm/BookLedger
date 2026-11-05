<?php

namespace App\Services;

use App\Models\DetallePrestamo;
use App\Models\Devolucion;
use App\Models\Prestamo;
use App\Models\Reserva;
use App\Models\Socio;
use App\Notifications\DevolucionRegistradaNotification;
use App\Notifications\ReservaDisponibleNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DevolucionService
{
    /** @param array<int,string> $items [Codigo_Detalle => EstadoEntrega] */
    public function registrar(array $items, ?string $observaciones, int $codigoBibliotecario): Devolucion
    {
        $devolucion = DB::transaction(function () use ($items, $observaciones, $codigoBibliotecario) {
            $detalles = DetallePrestamo::with(['prestamo', 'ejemplar'])
                ->whereIn('Codigo_Detalle', array_keys($items))
                ->whereNull('Codigo_Devolucion')
                ->lockForUpdate()
                ->get();

            if ($detalles->count() !== count($items)) {
                throw ValidationException::withMessages([
                    'detalles' => 'Alguno de los ejemplares ya fue devuelto. Recarga la página.',
                ]);
            }

            $hoy = today();
            $devolucion = Devolucion::create([
                'Codigo_Bibliotecario' => $codigoBibliotecario,
                'Fecha_Devolucion'     => $hoy,
                'Observaciones'        => $observaciones,
            ]);

            $sociosConMulta = [];

            foreach ($detalles as $d) {
                $estado = $items[$d->Codigo_Detalle];

                $d->update([
                    'Codigo_Devolucion' => $devolucion->Codigo_Devolucion,
                    'EstadoEntrega'     => $estado,
                ]);

                $d->ejemplar->update(['Estado_Ejemplar' => match ($estado) {
                    'Dañado'  => 'Mantenimiento',
                    'Perdido' => 'Perdido',
                    default   => 'Disponible',
                }]);

                $mora = max(0, (int) $d->prestamo->FechaLimite_Devolucion->diffInDays($hoy, false));
                $generoMulta = false;

                if ($mora > 0) {
                    $this->multa($d, $mora * config('biblioteca.multa_por_dia'), 'Mora');
                    $generoMulta = true;
                }
                if ($estado === 'Dañado') {
                    $this->multa($d, config('biblioteca.multa_dano'), 'Daño');
                    $generoMulta = true;
                }
                if ($estado === 'Perdido') {
                    $this->multa($d, config('biblioteca.multa_perdida'), 'Perdida');
                    $generoMulta = true;
                }
                if ($generoMulta) {
                    $sociosConMulta[] = $d->prestamo->Codigo_Socio;
                }
            }

            if ($sociosConMulta) {
                Socio::whereIn('Codigo_Socio', array_unique($sociosConMulta))
                    ->update(['EstadoSocio' => 'Sancionado']);
            }

            // Préstamos sin ejemplares pendientes quedan concluidos
            foreach ($detalles->pluck('Codigo_Prestamo')->unique() as $codigo) {
                $pendientes = DetallePrestamo::where('Codigo_Prestamo', $codigo)
                    ->whereNull('Codigo_Devolucion')->exists();

                if (!$pendientes) {
                    Prestamo::where('Codigo_Prestamo', $codigo)->update(['EstadoPrestamo' => 'Concluido']);
                }
            }

            $devolucion->setRelation('detalles', $detalles);
            return $devolucion;
        });

        $this->notificar($devolucion);

        return $devolucion;
    }

    private function multa(DetallePrestamo $detalle, float $monto, string $motivo): void
    {
        $detalle->multas()->create([
            'Monto'         => $monto,
            'Motivo'        => $motivo,
            'Estado_Pago'   => 'Pendiente',
            'Fecha_Emision' => today(),
        ]);
    }

    private function notificar(Devolucion $devolucion): void
    {
        /** @var Collection $detalles */
        $detalles = $devolucion->detalles->load('prestamo.socio', 'ejemplar.libro', 'multas');

        // Un aviso por socio (una devolución puede incluir ejemplares de varios)
        foreach ($detalles->groupBy(fn ($d) => $d->prestamo->Codigo_Socio) as $grupo) {
            $grupo->first()->prestamo->socio
                ->notify(new DevolucionRegistradaNotification($devolucion, $grupo));
        }

        // Por cada ejemplar que vuelve en buen estado, avisar al siguiente en la lista de espera
        $porIsbn = $detalles->where('EstadoEntrega', 'Buen estado')
            ->groupBy(fn ($d) => $d->ejemplar->Codigo_ISBN);

        foreach ($porIsbn as $isbn => $grupo) {
            Reserva::with('socio', 'libro')
                ->where('Codigo_ISBN', $isbn)
                ->where('EstadoReserva', 'Pendiente')
                ->orderBy('FechaReserva')->orderBy('Codigo_Reserva')
                ->limit($grupo->count())
                ->get()
                ->each(fn ($r) => $r->socio->notify(new ReservaDisponibleNotification($r)));
        }
    }
}