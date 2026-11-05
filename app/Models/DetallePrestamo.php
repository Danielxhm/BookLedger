<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePrestamo extends Model
{
    protected $table = 'detalle_prestamos';
    protected $primaryKey = 'Codigo_Detalle';
    public $timestamps = false;

    protected $fillable = [
        'Codigo_Prestamo', 'Codigo_Ejemplar', 'EstadoEntrega', 'Codigo_Devolucion',
        'fecha_modificacion', 'usuario_modificacion', 'motivo_modificacion',
    ];

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'Codigo_Prestamo', 'Codigo_Prestamo');
    }

    public function ejemplar()
    {
        return $this->belongsTo(Ejemplar::class, 'Codigo_Ejemplar', 'Codigo_Ejemplar');
    }

    public function devolucion()
    {
        return $this->belongsTo(Devolucion::class, 'Codigo_Devolucion', 'Codigo_Devolucion');
    }

    public function multas()
    {
        return $this->hasMany(Multa::class, 'Codigo_Detalle', 'Codigo_Detalle');
    }
}
