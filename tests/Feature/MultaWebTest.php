<?php

namespace Tests\Feature;

use App\Models\DetallePrestamo;
use App\Models\Multa;
use App\Models\Prestamo;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ConDatosDeBiblioteca;
use Tests\TestCase;

class MultaWebTest extends TestCase
{
    use ConDatosDeBiblioteca;
    use RefreshDatabase;

    public function test_se_puede_editar_una_multa_existente(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->syncRoles(['Administrador']);
        $biblio = $this->crearBibliotecario($user);
        $socio = $this->crearSocio();
        $libro = $this->crearLibroConEjemplares(1);
        $ejemplar = $libro->ejemplares->first();

        $prestamo = Prestamo::create([
            'Codigo_Socio' => $socio->Codigo_Socio,
            'Codigo_Bibliotecario' => $biblio->Codigo_Bibliotecario,
            'FechaPrestamo' => today()->subDays(20),
            'FechaLimite_Devolucion' => today()->subDays(6),
        ]);
        $detalle = DetallePrestamo::create([
            'Codigo_Prestamo' => $prestamo->Codigo_Prestamo,
            'Codigo_Ejemplar' => $ejemplar->Codigo_Ejemplar,
        ]);
        $multa = Multa::create([
            'Codigo_Detalle' => $detalle->Codigo_Detalle,
            'Monto' => 10.00,
            'Motivo' => 'Perdida',
            'Fecha_Emision' => today()->subDays(5),
        ]);

        $response = $this->actingAs($user)->put("/multas/{$multa->Codigo_Multa}", [
            'Codigo_Detalle' => $detalle->Codigo_Detalle,
            'Monto' => 2.00,
            'Motivo' => 'Daño',
            'Estado_Pago' => 'Pagado',
            'Fecha_Emision' => today()->subDays(5)->toDateString(),
        ]);

        $response->assertRedirect(route('multas.index'));
        $this->assertDatabaseHas('multas', [
            'Codigo_Multa' => $multa->Codigo_Multa,
            'Monto' => '2.00',
            'Motivo' => 'Daño',
            'Estado_Pago' => 'Pagado',
        ]);
    }

    public function test_rechaza_un_detalle_que_no_existe(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->syncRoles(['Administrador']);
        $this->crearBibliotecario($user);
        $socio = $this->crearSocio();
        $libro = $this->crearLibroConEjemplares(1);
        $ejemplar = $libro->ejemplares->first();

        $prestamo = Prestamo::create([
            'Codigo_Socio' => $socio->Codigo_Socio,
            'Codigo_Bibliotecario' => $user->bibliotecario->Codigo_Bibliotecario,
            'FechaPrestamo' => today(),
            'FechaLimite_Devolucion' => today()->addDays(14),
        ]);
        $detalle = DetallePrestamo::create([
            'Codigo_Prestamo' => $prestamo->Codigo_Prestamo,
            'Codigo_Ejemplar' => $ejemplar->Codigo_Ejemplar,
        ]);
        $multa = Multa::create([
            'Codigo_Detalle' => $detalle->Codigo_Detalle,
            'Monto' => 2.00,
            'Motivo' => 'Mora',
            'Fecha_Emision' => today(),
        ]);

        $response = $this->actingAs($user)->put("/multas/{$multa->Codigo_Multa}", [
            'Codigo_Detalle' => 999999,
            'Monto' => 2.00,
            'Motivo' => 'Mora',
            'Estado_Pago' => 'Pendiente',
            'Fecha_Emision' => today()->toDateString(),
        ]);

        $response->assertSessionHasErrors('Codigo_Detalle');
    }
}
