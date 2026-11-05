<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $table = 'prestamos';
    protected $primaryKey = 'Codigo_Prestamo';
    public $timestamps = false;

    protected $fillable = [
        'Codigo_Socio',
        'Codigo_Bibliotecario',
        'EstadoPrestamo',
        'FechaPrestamo',
        'FechaLimite_Devolucion',
        'fecha_modificacion',
        'usuario_modificacion',
        'motivo_modificacion',
    ];

    protected $attributes = ['EstadoPrestamo' => 'Activo'];

    protected $casts = [
        'FechaPrestamo' => 'date',
        'FechaLimite_Devolucion' => 'date',
    ];

    public function socio()
    {
        return $this->belongsTo(Socio::class, 'Codigo_Socio', 'Codigo_Socio');
    }

    public function bibliotecario()
    {
        return $this->belongsTo(Bibliotecario::class, 'Codigo_Bibliotecario', 'Codigo_Bibliotecario');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePrestamo::class, 'Codigo_Prestamo', 'Codigo_Prestamo');
    }
}
