<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds. (Roles y permisos para el sistema de gestión de biblioteca)
     */
    public function run(): void
    {
        $modulos = ['libros', 'categorias', 'editoriales', 'autores', 'ejemplares', 'socios', 'prestamos', 'devoluciones', 'reservas', 'multas'];

        foreach ($modulos as $modulo) {
            foreach (['ver', 'crear', 'editar', 'eliminar'] as $accion) {
                Permission::firstOrCreate(['name' => "{$modulo}.{$accion}"]);
            }
        }

        Permission::firstOrCreate(['name' => 'bibliotecarios.ver']);
        Permission::firstOrCreate(['name' => 'bibliotecarios.crear']);
        Permission::firstOrCreate(['name' => 'bibliotecarios.editar']);
        Permission::firstOrCreate(['name' => 'bibliotecarios.eliminar']);
        Permission::firstOrCreate(['name' => 'roles.gestionar']);

        $admin = Role::firstOrCreate(['name' => 'Administrador']);
        $admin->syncPermissions(Permission::all());

        $bibliotecario = Role::firstOrCreate(['name' => 'Bibliotecario']);
        $bibliotecario->syncPermissions(
            Permission::whereNotIn('name', ['bibliotecarios.ver', 'bibliotecarios.crear', 'bibliotecarios.editar', 'bibliotecarios.eliminar', 'roles.gestionar'])->get()
        );
    }
}
