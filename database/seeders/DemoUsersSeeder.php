<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Socio;
use App\Models\User;

//Seeder para crear usuarios de prueba (admin, bibliotecario y socio)

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@sgb.test'],
            ['name' => 'Administrador', 'password' => bcrypt('12345678')]
        );
        $admin->syncRoles(['Administrador']);

        $biblio = User::firstOrCreate(
            ['email' => 'biblio@sgb.test'],
            ['name' => 'Bibliotecario Prueba', 'password' => bcrypt('12345678')]
        );
        $biblio->syncRoles(['Bibliotecario']);

        Socio::firstOrCreate(
            ['Email' => 'socio@sgb.test'],
            [
                'Num_Documento' => '00000000-0',
                'Nombres' => 'Socio',
                'Apellidos' => 'Prueba',
                'Telefono' => '00000000',
                'Fecha_Nac' => '2000-01-01',
                'EstadoSocio' => 'Activo',
                'password' => bcrypt('12345678'),
            ]
        );
    }
}