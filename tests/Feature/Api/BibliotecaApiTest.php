<?php

namespace Tests\Feature\Api;

use App\Models\Autor;
use App\Models\DetallePrestamo;
use App\Models\Libro;
use App\Models\Multa;
use App\Models\Prestamo;
use App\Models\Socio;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ConDatosDeBiblioteca;
use Tests\TestCase;

class BibliotecaApiTest extends TestCase
{
    use ConDatosDeBiblioteca;
    use RefreshDatabase;

    private User $admin;

    private Socio $socio;

    private Libro $libro;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->syncRoles(['Administrador']);
        $this->crearBibliotecario($this->admin);

        $this->socio = $this->crearSocio();
        $this->libro = $this->crearLibroConEjemplares(2);
    }

    public function test_login_de_personal_devuelve_token_util(): void
    {
        $response = $this->postJson('/api/staff/login', [
            'email' => $this->admin->email,
            'password' => 'password',
        ]);

        $response->assertOk()->assertJsonStructure(['token', 'usuario' => ['roles']]);

        $this->getJson('/api/libros', ['Authorization' => 'Bearer ' . $response->json('token')])
            ->assertOk()
            ->assertJsonPath('data.0.Codigo_ISBN', $this->libro->Codigo_ISBN);
    }

    public function test_login_rechaza_credenciales_invalidas_y_usuarios_sin_rol(): void
    {
        $this->postJson('/api/staff/login', [
            'email' => $this->admin->email,
            'password' => 'clave-equivocada',
        ])->assertUnauthorized();

        $sinRol = User::factory()->create();
        $this->postJson('/api/staff/login', [
            'email' => $sinRol->email,
            'password' => 'password',
        ])->assertForbidden();
    }

    public function test_el_catalogo_requiere_token(): void
    {
        $this->getJson('/api/libros')->assertUnauthorized();
    }

    public function test_un_token_de_socio_no_abre_el_catalogo_del_personal(): void
    {
        $token = $this->socio->createToken('socio-api-token')->plainTextToken;

        $this->getJson('/api/libros', ['Authorization' => 'Bearer ' . $token])
            ->assertForbidden();
    }

    public function test_un_usuario_sin_permisos_no_puede_prestar(): void
    {
        $sinRol = User::factory()->create();

        $this->actingAs($sinRol, 'sanctum')
            ->postJson('/api/prestamos', [
                'Codigo_Socio' => $this->socio->Codigo_Socio,
                'ejemplares' => [$this->libro->ejemplares->first()->Codigo_Ejemplar],
            ])
            ->assertForbidden();
    }

    public function test_administrador_sin_ficha_presta_y_el_sistema_le_crea_ficha_sellada(): void
    {
        $adminSinFicha = User::factory()->create();
        $adminSinFicha->syncRoles(['Administrador']);

        $response = $this->actingAs($adminSinFicha, 'sanctum')
            ->postJson('/api/prestamos', [
                'Codigo_Socio' => $this->socio->Codigo_Socio,
                'ejemplares' => [$this->libro->ejemplares->first()->Codigo_Ejemplar],
            ]);

        $response->assertCreated();

        $ficha = $adminSinFicha->fresh()->bibliotecario;
        $this->assertNotNull($ficha, 'Un Administrador sin ficha debe recibir una automática.');
        $this->assertSame($adminSinFicha->name, $ficha->Nombres);
        $this->assertDatabaseHas('prestamos', [
            'Codigo_Prestamo' => $response->json('prestamo.Codigo_Prestamo'),
            'Codigo_Bibliotecario' => $ficha->Codigo_Bibliotecario,
        ]);
    }

    public function test_rol_bibliotecario_sin_ficha_sigue_sin_poder_prestar(): void
    {
        $biblioSinFicha = User::factory()->create();
        $biblioSinFicha->syncRoles(['Bibliotecario']);

        $this->actingAs($biblioSinFicha, 'sanctum')
            ->postJson('/api/prestamos', [
                'Codigo_Socio' => $this->socio->Codigo_Socio,
                'ejemplares' => [$this->libro->ejemplares->first()->Codigo_Ejemplar],
            ])
            ->assertForbidden();
    }

    public function test_prestar_y_devolver_por_api(): void
    {
        $ejemplar = $this->libro->ejemplares->first();

        $crear = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/prestamos', [
                'Codigo_Socio' => $this->socio->Codigo_Socio,
                'ejemplares' => [$ejemplar->Codigo_Ejemplar],
            ]);

        $crear->assertCreated();
        $this->assertDatabaseHas('ejemplars', [
            'Codigo_Ejemplar' => $ejemplar->Codigo_Ejemplar,
            'Estado_Ejemplar' => 'Prestado',
        ]);

        $prestamoId = $crear->json('prestamo.Codigo_Prestamo');
        $detalle = DetallePrestamo::where('Codigo_Prestamo', $prestamoId)->first();

        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/devoluciones', [
                'detalles' => [
                    $detalle->Codigo_Detalle => ['devolver' => true, 'estado' => 'Buen estado'],
                ],
            ])
            ->assertCreated();

        $this->assertDatabaseHas('ejemplars', [
            'Codigo_Ejemplar' => $ejemplar->Codigo_Ejemplar,
            'Estado_Ejemplar' => 'Disponible',
        ]);
        $this->assertDatabaseHas('prestamos', [
            'Codigo_Prestamo' => $prestamoId,
            'EstadoPrestamo' => 'Concluido',
        ]);
    }

    public function test_devolucion_con_mora_genera_multa_y_su_pago_reactiva_al_socio(): void
    {
        $ejemplar = $this->libro->ejemplares->first();
        $ejemplar->update(['Estado_Ejemplar' => 'Prestado']);

        $prestamo = Prestamo::create([
            'Codigo_Socio' => $this->socio->Codigo_Socio,
            'Codigo_Bibliotecario' => $this->admin->bibliotecario->Codigo_Bibliotecario,
            'EstadoPrestamo' => 'Activo',
            'FechaPrestamo' => today()->subDays(20),
            'FechaLimite_Devolucion' => today()->subDays(6),
        ]);
        $detalle = DetallePrestamo::create([
            'Codigo_Prestamo' => $prestamo->Codigo_Prestamo,
            'Codigo_Ejemplar' => $ejemplar->Codigo_Ejemplar,
        ]);

        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/devoluciones', [
                'detalles' => [
                    $detalle->Codigo_Detalle => ['devolver' => true, 'estado' => 'Buen estado'],
                ],
            ])
            ->assertCreated();

        $multa = Multa::first();
        $this->assertNotNull($multa, 'La mora debió generar una multa.');
        $this->assertSame('1.50', $multa->Monto);
        $this->assertSame('Pendiente', $multa->Estado_Pago);
        $this->assertSame('Sancionado', $this->socio->fresh()->EstadoSocio);

        $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/multas/{$multa->Codigo_Multa}/pagar")
            ->assertOk();

        $this->assertSame('Pagado', $multa->refresh()->Estado_Pago);
        $this->assertSame('Activo', $this->socio->fresh()->EstadoSocio);
    }

    public function test_catalogo_devuelve_libros_categorias_autores_y_editoriales(): void
    {
        $autor = Autor::create(['Nombres' => 'Ada', 'Apellidos' => 'Lovelace', 'Nacionalidad' => 'Británica']);
        $this->libro->autores()->attach($autor->Codigo_Autor, ['Tipo_Autoria' => 'Principal']);

        $this->actingAs($this->admin, 'sanctum');

        $this->getJson('/api/libros?buscar=prueba')
            ->assertOk()
            ->assertJsonPath('data.0.Codigo_ISBN', $this->libro->Codigo_ISBN)
            ->assertJsonPath('data.0.ejemplares_disponibles_count', 2);

        $this->getJson("/api/libros/{$this->libro->Codigo_ISBN}")
            ->assertOk()
            ->assertJsonPath('libro.autores.0.Codigo_Autor', $autor->Codigo_Autor)
            ->assertJsonCount(2, 'libro.ejemplares');

        $this->getJson('/api/categorias')
            ->assertOk()
            ->assertJsonPath('categorias.0.libros_count', 1);
        $this->getJson('/api/autores')
            ->assertOk()
            ->assertJsonPath('autores.0.libros_count', 1);
        $this->getJson('/api/editoriales')
            ->assertOk()
            ->assertJsonPath('editoriales.0.libros_count', 1);
        $this->getJson('/api/ejemplares?estado=Disponible')
            ->assertOk()
            ->assertJsonCount(2, 'data');
        $this->getJson('/api/socios')
            ->assertOk()
            ->assertJsonPath('data.0.Codigo_Socio', $this->socio->Codigo_Socio);
    }

    public function test_actividad_refleja_la_operacion_recien_registrada(): void
    {
        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/prestamos', [
                'Codigo_Socio' => $this->socio->Codigo_Socio,
                'ejemplares' => [$this->libro->ejemplares->first()->Codigo_Ejemplar],
            ])
            ->assertCreated();

        $response = $this->actingAs($this->admin, 'sanctum')->getJson('/api/actividad');

        $response->assertOk();

        $hoy = collect($response->json('dias'))->firstWhere('fecha', today()->toDateString());
        $this->assertSame(1, $hoy['prestamos']);
        $this->assertSame(1, $hoy['total']);
        $this->assertSame(1, $response->json('total'));

        $mesPasado = now()->subMonthNoOverflow();
        $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/actividad?fecha=' . $mesPasado->toDateString())
            ->assertOk()
            ->assertJsonPath('desde', $mesPasado->copy()->startOfMonth()->toDateString())
            ->assertJsonPath('hasta', $mesPasado->copy()->endOfMonth()->toDateString())
            ->assertJsonPath('total', 0);
    }

    public function test_dashboard_y_endpoint_de_socio_incluyen_multas_desglosadas_con_relaciones(): void
    {
        $ejemplar = $this->libro->ejemplares->first();
        $prestamo = Prestamo::create([
            'Codigo_Socio' => $this->socio->Codigo_Socio,
            'Codigo_Bibliotecario' => $this->admin->bibliotecario->Codigo_Bibliotecario,
            'FechaPrestamo' => today()->subDays(10),
            'FechaLimite_Devolucion' => today()->subDays(2),
        ]);
        $detalle = DetallePrestamo::create([
            'Codigo_Prestamo' => $prestamo->Codigo_Prestamo,
            'Codigo_Ejemplar' => $ejemplar->Codigo_Ejemplar,
        ]);
        $multa = Multa::create([
            'Codigo_Detalle' => $detalle->Codigo_Detalle,
            'Monto' => 2.00,
            'Motivo' => 'Daño',
            'Estado_Pago' => 'Pendiente',
            'Fecha_Emision' => today(),
        ]);

        // 1. El socio debe quedar automáticamente Sancionado por el observer de Multa
        $this->assertSame('Sancionado', $this->socio->fresh()->EstadoSocio);
        $this->assertNotEmpty($multa->usuario_modificacion);

        // 2. GET /api/socio/dashboard devuelve desglose de multas y relaciones
        $token = $this->socio->createToken('socio-token')->plainTextToken;
        $response = $this->getJson('/api/socio/dashboard', ['Authorization' => 'Bearer ' . $token]);

        $response->assertOk()
            ->assertJsonPath('socio.EstadoSocio', 'Sancionado')
            ->assertJsonPath('multas_pendientes', 2)
            ->assertJsonPath('multas.0.Codigo_Multa', $multa->Codigo_Multa)
            ->assertJsonPath('multas.0.detalle.ejemplar.libro.Codigo_ISBN', $this->libro->Codigo_ISBN);

        // 3. GET /api/socio/multas devuelve la lista
        $this->getJson('/api/socio/multas', ['Authorization' => 'Bearer ' . $token])
            ->assertOk()
            ->assertJsonPath('total_pendiente', 2)
            ->assertJsonPath('multas.0.Codigo_Multa', $multa->Codigo_Multa);
    }

    public function test_socio_sancionado_con_multas_puede_iniciar_sesion_para_ver_sus_cargos_pero_no_reservar(): void
    {
        // 1. Socio sancionado con multa
        $socioSancionado = $this->crearSocio([
            'Num_Documento' => '33333333-3',
            'Email' => 'jose.luis@sgb.test',
            'password' => 'password123',
            'EstadoSocio' => 'Sancionado',
        ]);

        // Debe poder iniciar sesión con sus credenciales
        $loginResponse = $this->postJson('/api/socio/login', [
            'Email' => 'jose.luis@sgb.test',
            'password' => 'password123',
        ]);

        $loginResponse->assertOk()
            ->assertJsonStructure(['token', 'socio' => ['Codigo_Socio', 'EstadoSocio']])
            ->assertJsonPath('socio.EstadoSocio', 'Sancionado');

        $token = $loginResponse->json('token');

        // Puede consultar su dashboard de multas
        $this->getJson('/api/socio/dashboard', ['Authorization' => 'Bearer ' . $token])
            ->assertOk();

        // Pero NO puede crear nuevas reservas mientras esté sancionado
        $reservaResponse = $this->postJson('/api/socio/reservas', [
            'Codigo_ISBN' => $this->libro->Codigo_ISBN,
        ], ['Authorization' => 'Bearer ' . $token]);

        $reservaResponse->assertForbidden();

        // 2. Socio inactivo (dado de baja) no puede entrar
        $socioInactivo = $this->crearSocio([
            'Num_Documento' => '44444444-4',
            'Email' => 'baja@sgb.test',
            'password' => 'password123',
            'EstadoSocio' => 'Inactivo',
        ]);

        $this->postJson('/api/socio/login', [
            'Email' => 'baja@sgb.test',
            'password' => 'password123',
        ])->assertForbidden();
    }
}
