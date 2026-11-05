<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class BibliotecariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds. (Seeds de la tabla bibliotecarios)
     */
    public function run(): void
    {
        DB::table('bibliotecarios')->insert([
            [
                'Num_documento'  => '12212111-1',
                'Nombres'        => 'Admin',
                'Apellidos'      => 'User',
                'NombreUsuario'  => 'admin',
                'clave_hash'     => Hash::make('password123'),
                'EstadoEmpleado' => 'Activo',
                'user_id'        => User::where('email', 'admin@sgb.test')->value('id'),
            ],
            [
                'Num_documento'  => '00000001-1',
                'Nombres'        => 'Bibliotecario',
                'Apellidos'      => 'Prueba',
                'NombreUsuario'  => 'biblio',
                'clave_hash'     => Hash::make('password123'),
                'EstadoEmpleado' => 'Activo',
                'user_id'        => User::where('email', 'biblio@sgb.test')->value('id'),
            ],
        ]);
    }
}
