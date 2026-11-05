<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Socio extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'socios';
    protected $primaryKey = 'Codigo_Socio';
    public $timestamps = false;

    protected $fillable = [
        'Num_Documento',
        'Nombres',
        'Apellidos',
        'Email',
        'Telefono',
        'EstadoSocio',
        'Fecha_Nac',
        'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'Fecha_Nac' => 'date',
        'Fecha_RegistroSocio' => 'date',
        'password' => 'hashed',
    ];

    public function getRouteKeyName()
    {
        return 'Codigo_Socio';
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'Codigo_Socio', 'Codigo_Socio');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'Codigo_Socio', 'Codigo_Socio');
    }
    public function multas()
    {
        return Multa::whereIn('Codigo_Detalle', function ($q) {
            $q->select('Codigo_Detalle')
                ->from('detalle_prestamos')
                ->whereIn('Codigo_Prestamo', function ($q2) {
                    $q2->select('Codigo_Prestamo')
                        ->from('prestamos')
                        ->where('Codigo_Socio', $this->Codigo_Socio);
                });
        });
    }

    public function multasPendientes()
    {
        return $this->multas()->where('Estado_Pago', 'Pendiente');
    }
    public function ejemplaresEnPoder(): int
    {
        return Ejemplar::whereIn('Codigo_Ejemplar', function ($q) {
            $q->select('Codigo_Ejemplar')
                ->from('detalle_prestamos')
                ->whereIn('Codigo_Prestamo', function ($q2) {
                    $q2->select('Codigo_Prestamo')
                        ->from('prestamos')
                        ->where('Codigo_Socio', $this->Codigo_Socio)
                        ->whereIn('EstadoPrestamo', ['Activo', 'Vencido']);
                });
        })->count();
    }
}
