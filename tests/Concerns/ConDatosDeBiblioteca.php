<?php

namespace Tests\Concerns;

use App\Models\Bibliotecario;
use App\Models\Categoria;
use App\Models\Editorial;
use App\Models\Ejemplar;
use App\Models\Libro;
use App\Models\Socio;
use App\Models\User;

trait ConDatosDeBiblioteca
{
    protected function crearSocio(array $extra = []): Socio
    {
        return Socio::create(array_merge([
            'Num_Documento' => '11111111-1',
            'Nombres' => 'Ana',
            'Apellidos' => 'Pérez',
            'Email' => 'ana@sgb.test',
            'Telefono' => '12345678',
            'Fecha_Nac' => '1990-01-01',
            'EstadoSocio' => 'Activo',
            'password' => 'password123',
        ], $extra));
    }

    protected function crearBibliotecario(User $user, array $extra = []): Bibliotecario
    {
        return Bibliotecario::create(array_merge([
            'Num_documento' => '22222222-2',
            'Nombres' => 'Beto',
            'Apellidos' => 'Biblioteca',
            'NombreUsuario' => 'beto',
            'clave_hash' => bcrypt('password123'),
            'EstadoEmpleado' => 'Activo',
            'user_id' => $user->id,
        ], $extra));
    }

    protected function crearLibroConEjemplares(int $cuantos = 1, array $extra = []): Libro
    {
        $categoria = Categoria::create(['NombreCategoria' => 'Novela de prueba']);
        $editorial = Editorial::create(['NombreEditorial' => 'Editorial de prueba', 'Pais' => 'España']);

        $libro = Libro::create(array_merge([
            'Codigo_ISBN' => '978-0-00-000000-0',
            'Titulo' => 'Libro de prueba',
            'Anio_Publicacion' => 2020,
            'N_paginas' => 100,
            'Codigo_Categoria' => $categoria->Codigo_Categoria,
            'Codigo_Editorial' => $editorial->Codigo_Editorial,
        ], $extra));

        for ($i = 0; $i < $cuantos; $i++) {
            Ejemplar::create([
                'Codigo_ISBN' => $libro->Codigo_ISBN,
                'Ubicacion_Estante' => 'A-' . $i,
                'Estado_Ejemplar' => 'Disponible',
            ]);
        }

        return $libro->fresh();
    }
}
