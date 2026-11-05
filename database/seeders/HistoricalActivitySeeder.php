<?php

namespace Database\Seeders;

use App\Models\DetallePrestamo;
use App\Models\Devolucion;
use App\Models\Ejemplar;
use App\Models\Libro;
use App\Models\Multa;
use App\Models\Prestamo;
use App\Models\Reserva;
use App\Models\Socio;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Genera circulación histórica coherente (2025-01 a 2026-11) para que el
 * heatmap, los KPIs y la API muestren actividad real y variada.
 * Idempotente: al correr de nuevo elimina primero todo lo que sembró (socios HIST-*).
 */
class HistoricalActivitySeeder extends Seeder
{
    /** Préstamos por mes con estacionalidad tipo año lectivo. */
    private const PRESTAMOS_POR_MES = [
        '2025-01' => 3, '2025-02' => 4, '2025-03' => 8, '2025-04' => 9,
        '2025-05' => 8, '2025-06' => 5, '2025-07' => 2, '2025-08' => 3,
        '2025-09' => 9, '2025-10' => 8, '2025-11' => 6, '2025-12' => 3,
        '2026-01' => 4, '2026-02' => 5, '2026-03' => 9, '2026-04' => 8,
        '2026-05' => 7, '2026-06' => 4, '2026-07' => 2, '2026-08' => 3,
        '2026-09' => 8, '2026-10' => 7, '2026-11' => 2,
    ];

    private const NOMBRES = [
        'María José', 'Carlos', 'Fernanda', 'José Luis', 'Ana Gabriela', 'Ricardo',
        'Paula', 'Andrés', 'Lucía', 'Miguel Ángel', 'Valeria', 'Diego',
        'Camila', 'Roberto',
    ];

    private const APELLIDOS = [
        'Martínez Rodríguez', 'López Hernández', 'García Pérez', 'Torres Ramírez',
        'Flores Mendoza', 'Cruz Morales', 'Reyes Ortiz', 'Gutiérrez Castro',
        'Chávez Rojas', 'Ramos Vargas', 'Silva Núñez', 'Mendoza Aguilar',
    ];

    public function run(): void
    {
        mt_srand(20261103);
        $hoy = today();

        DB::transaction(function () use ($hoy) {
            $this->limpiarHistorial();
            $socios = $this->crearSocios();
            $this->generarCirculacion($socios, $hoy);
            $this->generarReservas($socios, $hoy);
            $this->estadoFinalSocios($socios);
        });

        $this->resumen();
    }

    /** Borra un sembrado previo (solo datos marcados HIST-) en orden de claves foráneas. */
    private function limpiarHistorial(): void
    {
        $socios = Socio::where('Num_Documento', 'like', 'HIST-%')->pluck('Codigo_Socio');
        if ($socios->isEmpty()) {
            return;
        }

        $prestamos = Prestamo::whereIn('Codigo_Socio', $socios)->pluck('Codigo_Prestamo');
        $detalles = DB::table('detalle_prestamos')
            ->whereIn('Codigo_Prestamo', $prestamos)
            ->get(['Codigo_Detalle', 'Codigo_Ejemplar', 'Codigo_Devolucion']);

        Multa::whereIn('Codigo_Detalle', $detalles->pluck('Codigo_Detalle'))->delete();
        DB::table('detalle_prestamos')->whereIn('Codigo_Prestamo', $prestamos)->delete();
        Devolucion::whereIn('Codigo_Devolucion', $detalles->pluck('Codigo_Devolucion')->filter())->delete();
        Prestamo::whereIn('Codigo_Prestamo', $prestamos)->delete();
        Reserva::whereIn('Codigo_Socio', $socios)->delete();
        Ejemplar::whereIn('Codigo_Ejemplar', $detalles->pluck('Codigo_Ejemplar'))
            ->update(['Estado_Ejemplar' => 'Disponible']);
        Socio::whereIn('Codigo_Socio', $socios)->delete();
    }

    /** @return array<int, Socio> */
    private function crearSocios(): array
    {
        $socios = [];

        foreach (range(1, count(self::NOMBRES)) as $i) {
            $socios[] = Socio::create([
                'Num_Documento' => 'HIST-' . str_pad((string) $i, 4, '0', \STR_PAD_LEFT),
                'Nombres' => self::NOMBRES[$i - 1],
                'Apellidos' => self::APELLIDOS[($i * 5) % count(self::APELLIDOS)],
                'Email' => "hist.socio{$i}@sgb.test",
                'Telefono' => '7' . str_pad((string) mt_rand(0, 9999999), 7, '0', \STR_PAD_LEFT),
                'Fecha_Nac' => Carbon::create(mt_rand(1968, 2004), mt_rand(1, 12), mt_rand(1, 28)),
                'Fecha_RegistroSocio' => Carbon::create(2025, 1, 1)->addDays(mt_rand(0, 320)),
                'EstadoSocio' => 'Activo',
                'password' => 'password123',
            ]);
        }

        return $socios;
    }

    /** @param array<int, Socio> $socios */
    private function generarCirculacion(array $socios, Carbon $hoy): void
    {
        $bibliotecarios = [1, 2];
        $ejemplares = Ejemplar::pluck('Codigo_Ejemplar')->all();
        $libreDesde = array_fill_keys($ejemplares, Carbon::create(2025, 1, 1));
        $estadoFinal = [];
        $perdidos = [];
        $diasPrestamo = (int) config('biblioteca.dias_prestamo');

        // La deuda pendiente se concentra en unos pocos socios para que el
        // resto conserve historial con multas ya pagadas.
        $orden = $socios;
        shuffle($orden);
        $deudores = array_slice($orden, 0, 4);
        $esDeudor = fn (Socio $s) => in_array($s->Codigo_Socio, array_map(fn ($d) => $d->Codigo_Socio, $deudores), true);

        foreach (self::PRESTAMOS_POR_MES as $mes => $cantidad) {
            [$anio, $mm] = array_map('intval', explode('-', $mes));

            for ($n = 0; $n < $cantidad; $n++) {
                $fecha = Carbon::create($anio, $mm, 1)->addDays(mt_rand(0, 27));
                if ($fecha->gt($hoy)) {
                    continue;
                }

                $socio = $socios[array_rand($socios)];
                $libres = array_values(array_filter(
                    $ejemplares,
                    fn ($e) => ! isset($perdidos[$e]) && $libreDesde[$e]->lte($fecha)
                ));
                if ($libres === []) {
                    continue;
                }
                shuffle($libres);
                $elegidos = array_slice($libres, 0, mt_rand(1, 2));

                $offset = mt_rand(4, 24);
                $fechaDev = $fecha->copy()->addDays($offset);
                $activo = $fechaDev->gt($hoy);

                $prestamo = Prestamo::create([
                    'Codigo_Socio' => $socio->Codigo_Socio,
                    'Codigo_Bibliotecario' => $bibliotecarios[array_rand($bibliotecarios)],
                    'EstadoPrestamo' => $activo ? 'Activo' : 'Concluido',
                    'FechaPrestamo' => $fecha,
                    'FechaLimite_Devolucion' => $fecha->copy()->addDays($diasPrestamo),
                ]);

                $devolucion = $activo ? null : Devolucion::create([
                    'Codigo_Bibliotecario' => $prestamo->Codigo_Bibliotecario,
                    'Fecha_Devolucion' => $fechaDev,
                    'Observaciones' => mt_rand(1, 4) === 1 ? 'Devolución en mostrador' : null,
                ]);

                foreach ($elegidos as $codigoEjemplar) {
                    $estadoEntrega = 'Buen estado';
                    if (! $activo) {
                        $r = mt_rand(1, 100);
                        $estadoEntrega = $r <= 85 ? 'Buen estado' : ($r <= 95 ? 'Dañado' : 'Perdido');
                    }

                    $detalle = DetallePrestamo::create([
                        'Codigo_Prestamo' => $prestamo->Codigo_Prestamo,
                        'Codigo_Ejemplar' => $codigoEjemplar,
                        'Codigo_Devolucion' => $devolucion?->Codigo_Devolucion,
                        'EstadoEntrega' => $devolucion ? $estadoEntrega : null,
                    ]);

                    if ($activo) {
                        $libreDesde[$codigoEjemplar] = Carbon::create(9999, 1, 1);
                        $estadoFinal[$codigoEjemplar] = 'Prestado';
                        continue;
                    }

                    $this->multasDelDetalle($detalle, $offset - $diasPrestamo, $estadoEntrega, $fechaDev, $hoy, $esDeudor($socio));

                    if ($estadoEntrega === 'Perdido') {
                        $perdidos[$codigoEjemplar] = true;
                        $estadoFinal[$codigoEjemplar] = 'Perdido';
                    } elseif ($estadoEntrega === 'Dañado') {
                        $saleDeMantenimiento = $fechaDev->copy()->addDays(21);
                        $libreDesde[$codigoEjemplar] = $saleDeMantenimiento;
                        $estadoFinal[$codigoEjemplar] = $saleDeMantenimiento->gt($hoy) ? 'Mantenimiento' : 'Disponible';
                    } else {
                        $libreDesde[$codigoEjemplar] = $fechaDev;
                        $estadoFinal[$codigoEjemplar] = 'Disponible';
                    }
                }
            }
        }

        // Préstamos recientes aún activos (algunos ya vencidos) para dar vida al panel.
        for ($i = 0; $i < 6; $i++) {
            $fecha = $hoy->copy()->subDays(mt_rand(2, 20));
            $libres = array_values(array_filter(
                $ejemplares,
                fn ($e) => ! isset($perdidos[$e])
                    && $libreDesde[$e]->lte($fecha)
                    && ($estadoFinal[$e] ?? 'Disponible') === 'Disponible'
            ));
            if ($libres === []) {
                continue;
            }
            shuffle($libres);

            $prestamo = Prestamo::create([
                'Codigo_Socio' => $socios[array_rand($socios)]->Codigo_Socio,
                'Codigo_Bibliotecario' => $bibliotecarios[array_rand($bibliotecarios)],
                'EstadoPrestamo' => 'Activo',
                'FechaPrestamo' => $fecha,
                'FechaLimite_Devolucion' => $fecha->copy()->addDays($diasPrestamo),
            ]);

            foreach (array_slice($libres, 0, mt_rand(1, 2)) as $codigoEjemplar) {
                DetallePrestamo::create([
                    'Codigo_Prestamo' => $prestamo->Codigo_Prestamo,
                    'Codigo_Ejemplar' => $codigoEjemplar,
                ]);
                $libreDesde[$codigoEjemplar] = Carbon::create(9999, 1, 1);
                $estadoFinal[$codigoEjemplar] = 'Prestado';
            }
        }

        foreach ($estadoFinal as $codigoEjemplar => $estado) {
            Ejemplar::where('Codigo_Ejemplar', $codigoEjemplar)->update(['Estado_Ejemplar' => $estado]);
        }
    }

    private function multasDelDetalle(DetallePrestamo $detalle, int $mora, string $estadoEntrega, Carbon $fechaDev, Carbon $hoy, bool $esDeudor): void
    {
        if (! $esDeudor) {
            $pagada = true;
        } else {
            $pagada = $fechaDev->lte($hoy->copy()->subDays(60)) ? mt_rand(1, 100) <= 60 : mt_rand(1, 100) <= 20;
        }

        $motivos = [];
        if ($mora > 0) {
            $motivos['Mora'] = $mora * (float) config('biblioteca.multa_por_dia');
        }
        if ($estadoEntrega === 'Dañado') {
            $motivos['Daño'] = (float) config('biblioteca.multa_dano');
        }
        if ($estadoEntrega === 'Perdido') {
            $motivos['Perdida'] = (float) config('biblioteca.multa_perdida');
        }

        foreach ($motivos as $motivo => $monto) {
            Multa::create([
                'Codigo_Detalle' => $detalle->Codigo_Detalle,
                'Monto' => $monto,
                'Motivo' => $motivo,
                'Estado_Pago' => $pagada ? 'Pagado' : 'Pendiente',
                'Fecha_Emision' => $fechaDev,
            ]);
        }
    }

    /** @param array<int, Socio> $socios */
    private function generarReservas(array $socios, Carbon $hoy): void
    {
        $isbns = Libro::pluck('Codigo_ISBN')->all();
        shuffle($isbns);

        $plan = [
            ['Pendiente', fn () => $hoy->copy()->subDays(mt_rand(1, 45))],
            ['Pendiente', fn () => $hoy->copy()->subDays(mt_rand(1, 45))],
            ['Pendiente', fn () => $hoy->copy()->subDays(mt_rand(1, 45))],
            ['Pendiente', fn () => $hoy->copy()->subDays(mt_rand(1, 45))],
            ['Atendida', fn () => Carbon::create(2026, mt_rand(3, 8), mt_rand(1, 28))],
            ['Atendida', fn () => Carbon::create(2026, mt_rand(3, 8), mt_rand(1, 28))],
            ['Cancelada', fn () => Carbon::create(2025, mt_rand(5, 12), mt_rand(1, 28))],
            ['Cancelada', fn () => Carbon::create(2025, mt_rand(5, 12), mt_rand(1, 28))],
        ];

        foreach ($plan as $i => [$estado, $fecha]) {
            Reserva::create([
                'Codigo_Socio' => $socios[array_rand($socios)]->Codigo_Socio,
                'Codigo_ISBN' => $isbns[$i],
                'FechaReserva' => $fecha(),
                'EstadoReserva' => $estado,
            ]);
        }
    }

    /** @param array<int, Socio> $socios */
    private function estadoFinalSocios(array $socios): void
    {
        foreach ($socios as $socio) {
            $pendientes = Multa::where('Estado_Pago', 'Pendiente')
                ->whereHas('detalle.prestamo', fn ($q) => $q->where('Codigo_Socio', $socio->Codigo_Socio))
                ->exists();

            $socio->update([
                'EstadoSocio' => $pendientes ? 'Sancionado' : (mt_rand(1, 10) === 1 ? 'Inactivo' : 'Activo'),
            ]);
        }
    }

    private function resumen(): void
    {
        $conteo = fn (string $tabla, string $columna) => json_encode(
            DB::table($tabla)->selectRaw("$columna as k, COUNT(*) as n")->groupBy($columna)->pluck('n', 'k')
        );

        echo "Socios HIST: " . $conteo('socios', 'EstadoSocio') . "\n";
        echo "Préstamos: " . $conteo('prestamos', 'EstadoPrestamo') . "\n";
        echo "Multas: " . $conteo('multas', 'Estado_Pago') . "\n";
        echo "Reservas: " . $conteo('reservas', 'EstadoReserva') . "\n";
        echo 'Devoluciones: ' . Devolucion::count() . "\n";
    }
}
