<?php

namespace Tests\Feature;

use Tests\TestCase;

class InstallerTest extends TestCase
{
    private bool $estabaInstalado = false;

    private ?string $contenidoOriginal = null;

    protected function setUp(): void
    {
        parent::setUp();

        $lockFile = storage_path('installed');
        $this->estabaInstalado = file_exists($lockFile);
        $this->contenidoOriginal = $this->estabaInstalado ? file_get_contents($lockFile) : null;

        // El instalador solo se prueba con el sistema sin instalar; tearDown restaura el estado real.
        if ($this->estabaInstalado) {
            @unlink($lockFile);
        }
    }

    protected function tearDown(): void
    {
        $lockFile = storage_path('installed');

        if ($this->estabaInstalado) {
            file_put_contents($lockFile, $this->contenidoOriginal);
        } elseif (file_exists($lockFile)) {
            @unlink($lockFile);
        }

        parent::tearDown();
    }

    public function test_redirects_to_installer_when_not_installed(): void
    {
        $lockFile = storage_path('installed');
        if (file_exists($lockFile)) {
            @unlink($lockFile);
        }

        $response = $this->get('/');
        $response->assertRedirect(route('install.index'));
    }

    public function test_installer_step1_loads_successfully(): void
    {
        $response = $this->get(route('install.index'));
        $response->assertStatus(200);
        $response->assertSee('Comprobación de Requisitos del Servidor');
    }

    public function test_installer_step2_loads_successfully(): void
    {
        $response = $this->get(route('install.database'));
        $response->assertStatus(200);
        $response->assertSee('Configuración de Base de Datos y Super Administrador');
    }

    public function test_blocks_access_to_installer_when_already_installed(): void
    {
        $lockFile = storage_path('installed');
        file_put_contents($lockFile, 'LOCKED');

        try {
            $response = $this->get(route('install.index'));
            $response->assertRedirect(route('login'));

            $response2 = $this->get(route('install.database'));
            $response2->assertRedirect(route('login'));
        } finally {
            if (file_exists($lockFile)) {
                @unlink($lockFile);
            }
        }
    }
}
