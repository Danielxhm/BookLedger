<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ejemplar extends Model
{
    protected $table = 'ejemplars';
    protected $primaryKey = 'Codigo_Ejemplar';
    public $timestamps = false;
    protected $fillable = ['Codigo_ISBN', 'Ubicacion_Estante', 'Estado_Ejemplar', 'Fecha_Adquisicion'];

    protected $casts = ['Fecha_Adquisicion' => 'date'];
    public function libro()
    {
        return $this->belongsTo(Libro::class, 'Codigo_ISBN', 'Codigo_ISBN');
    }

    public function detalles()
{
    return $this->hasMany(DetallePrestamo::class, 'Codigo_Ejemplar', 'Codigo_Ejemplar');
}

public function detalleActivo()
{
    return $this->hasOne(DetallePrestamo::class, 'Codigo_Ejemplar', 'Codigo_Ejemplar')
        ->whereNull('Codigo_Devolucion');
}
}
