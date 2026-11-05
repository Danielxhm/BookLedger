<?php

namespace Tests\Feature;

use App\Models\Ejemplar;
use App\Models\Prestamo;
use App\Models\Reserva;
use App\Models\Socio;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ConDatosDeBiblioteca;
use Tests\TestCase;

class GestionReservasEnPrestamosTest extends TestCase
{
    use ConDatosDeBiblioteca;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\Notification::fake();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_panel_prestamos_muestra_contador_de_reservas_pendientes(): void
    {
        $user = User::factory()->create();
        $user->syncRoles(['Administrador']);
        $this->crearBibliotecario($user);

        $socio = $this->crearSocio();
        $libro = $this->crearLibroConEjemplares(2);

        Reserva::create([
            'Codigo_Socio' => $socio->Codigo_Socio,
            'Codigo_ISBN' => $libro->Codigo_ISBN,
            'FechaReserva' => today(),
            'EstadoReserva' => 'Pendiente',
        ]);

        $response = $this->actingAs($user)->get(route('prestamos.index'));

        $response->assertStatus(200);
        $response->assertSee('Solicitudes de reserva');
        $response->assertSee('Hay 1 reserva de libro pendiente');
    }

    public function test_pestana_reservas_lista_solicitudes_y_permite_busqueda(): void
    {
        $user = User::factory()->create();
        $user->syncRoles(['Administrador']);
        $this->crearBibliotecario($user);

        $socio = $this->crearSocio(['Nombres' => 'Valentina', 'Apellidos' => 'Gómez']);
        $libro = $this->crearLibroConEjemplares(1, ['Titulo' => 'Cien años de soledad']);

        $reserva = Reserva::create([
            'Codigo_Socio' => $socio->Codigo_Socio,
            'Codigo_ISBN' => $libro->Codigo_ISBN,
            'FechaReserva' => today(),
            'EstadoReserva' => 'Pendiente',
        ]);

        // Ver pestaña reservas
        $response = $this->actingAs($user)->get(route('prestamos.index', ['tab' => 'reservas']));
        $response->assertStatus(200);
        $response->assertSee('Valentina Gómez');
        $response->assertSee('Cien años de soledad');
        $response->assertSee('Entregar libro');

        // Buscar por título
        $responseSearch = $this->actingAs($user)->get(route('prestamos.index', ['tab' => 'reservas', 'buscar' => 'soledad']));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Cien años de soledad');

        // Buscar término inexistente
        $responseEmpty = $this->actingAs($user)->get(route('prestamos.index', ['tab' => 'reservas', 'buscar' => 'InexistenteXYZ']));
        $responseEmpty->assertStatus(200);
        $responseEmpty->assertSee('No hay reservas que coincidan');
    }

    public function test_entregar_reserva_crea_prestamo_y_marca_reserva_atendida(): void
    {
        $user = User::factory()->create();
        $user->syncRoles(['Administrador']);
        $this->crearBibliotecario($user);

        $socio = $this->crearSocio();
        $libro = $this->crearLibroConEjemplares(1);
        $ejemplar = $libro->ejemplares->first();

        $reserva = Reserva::create([
            'Codigo_Socio' => $socio->Codigo_Socio,
            'Codigo_ISBN' => $libro->Codigo_ISBN,
            'FechaReserva' => today(),
            'EstadoReserva' => 'Pendiente',
        ]);

        $response = $this->actingAs($user)->patch(route('prestamos.reservas.entregar', $reserva));

        $response->assertRedirect(route('prestamos.index', ['tab' => 'reservas']));
        $response->assertSessionHas('status');

        $reserva->refresh();
        $this->assertEquals('Atendida', $reserva->EstadoReserva);

        $ejemplar->refresh();
        $this->assertEquals('Prestado', $ejemplar->Estado_Ejemplar);

        $this->assertDatabaseHas('prestamos', [
            'Codigo_Socio' => $socio->Codigo_Socio,
            'EstadoPrestamo' => 'Activo',
        ]);
    }

    public function test_cancelar_reserva_cambia_su_estado_a_cancelada(): void
    {
        $user = User::factory()->create();
        $user->syncRoles(['Administrador']);
        $this->crearBibliotecario($user);

        $socio = $this->crearSocio();
        $libro = $this->crearLibroConEjemplares(1);

        $reserva = Reserva::create([
            'Codigo_Socio' => $socio->Codigo_Socio,
            'Codigo_ISBN' => $libro->Codigo_ISBN,
            'FechaReserva' => today(),
            'EstadoReserva' => 'Pendiente',
        ]);

        $response = $this->actingAs($user)->patch(route('prestamos.reservas.cancelar', $reserva));

        $response->assertRedirect(route('prestamos.index', ['tab' => 'reservas']));
        $reserva->refresh();
        $this->assertEquals('Cancelada', $reserva->EstadoReserva);
    }
}
