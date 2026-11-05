<?php

namespace Tests\Feature;

use App\Models\Bibliotecario;
use App\Models\Devolucion;
use App\Models\Ejemplar;
use App\Models\Prestamo;
use App\Models\Socio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ConDatosDeBiblioteca;
use Tests\TestCase;

class DashboardActividadTest extends TestCase
{
    use ConDatosDeBiblioteca;
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setLocale('es');
        $this->user = User::factory()->create();
    }

    public function test_el_dashboard_requiere_autenticacion(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_el_heatmap_solo_cuenta_actividad_real(): void
    {
        $socio = $this->crearSocio();
        $biblio = $this->crearBibliotecario($this->user);
        $libro = $this->crearLibroConEjemplares(2);
        $ejemplares = $libro->ejemplares;

        $this->prestamo($socio, $biblio, today(), $ejemplares[0]);
        $this->prestamo($socio, $biblio, today()->subDays(3), $ejemplares[1]);
        Devolucion::create([
            'Codigo_Bibliotecario' => $biblio->Codigo_Bibliotecario,
            'Fecha_Devolucion' => today(),
        ]);

        $response = $this->actingAs($this->user)->get('/dashboard');

        $response->assertOk();
        $response->assertViewHas('totalOperacionesHeatmap', 3);
        $this->assertCount(53, $response->viewData('heatmapWeeks'));

        $dias = $this->diasDelHeatmap($response);

        $this->assertSame(2, $dias[today()->toDateString()]['count']);
        $this->assertSame(1, $dias[today()->subDays(3)->toDateString()]['count']);
        $this->assertSame(0, $dias[today()->subDays(10)->toDateString()]['count']);
    }

    public function test_el_filtro_de_fecha_limita_el_heatmap_al_mes_elegido(): void
    {
        $socio = $this->crearSocio();
        $biblio = $this->crearBibliotecario($this->user);
        $libro = $this->crearLibroConEjemplares(1);
        $ejemplar = $libro->ejemplares->first();

        $mesPasado = now()->subMonthNoOverflow();
        $this->prestamo($socio, $biblio, $mesPasado->copy()->startOfMonth()->addDays(2), $ejemplar);
        $this->prestamo($socio, $biblio, today(), $ejemplar);

        $response = $this->actingAs($this->user)
            ->get('/dashboard?fecha=' . $mesPasado->toDateString());

        $response->assertOk();
        $response->assertViewHas('totalOperacionesHeatmap', 1);
        $this->assertCount(53, $response->viewData('heatmapWeeks'));

        $dias = $this->diasDelHeatmap($response);

        $this->assertNotEmpty($dias);
        foreach ($dias as $dateStr => $day) {
            $this->assertSame($mesPasado->format('Y-m'), Carbon::parse($dateStr)->format('Y-m'), "El día {$dateStr} no pertenece al mes filtrado");
        }

        $this->assertSame(
            ucfirst($mesPasado->translatedFormat('F Y')),
            $response->viewData('heatmapEtiqueta')
        );
    }

    /** @return array<string, array{date: string, count: int, blank: bool}> */
    private function diasDelHeatmap($response): array
    {
        return collect($response->viewData('heatmapWeeks'))
            ->flatMap(fn ($week) => $week['days'])
            ->filter(fn ($day) => $day['date'] !== null && ! $day['blank'])
            ->keyBy('date')
            ->all();
    }

    private function prestamo(Socio $socio, Bibliotecario $biblio, Carbon $fecha, Ejemplar $ejemplar): Prestamo
    {
        $ejemplar->update(['Estado_Ejemplar' => 'Prestado']);

        return Prestamo::create([
            'Codigo_Socio' => $socio->Codigo_Socio,
            'Codigo_Bibliotecario' => $biblio->Codigo_Bibliotecario,
            'EstadoPrestamo' => 'Activo',
            'FechaPrestamo' => $fecha,
            'FechaLimite_Devolucion' => $fecha->copy()->addDays(14),
        ]);
    }
}
