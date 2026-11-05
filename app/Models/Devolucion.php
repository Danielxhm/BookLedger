<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = 'devolucions';
    protected $primaryKey = 'Codigo_Devolucion';
    public $timestamps = false;

    protected $fillable = [
        'Codigo_Bibliotecario',
        'Fecha_Devolucion',
        'Observaciones',
        'fecha_modificacion',
        'usuario_modificacion',
        'motivo_modificacion',
    ];

    protected $casts = ['Fecha_Devolucion' => 'date'];

    public function bibliotecario()
    {
        return $this->belongsTo(Bibliotecario::class, 'Codigo_Bibliotecario', 'Codigo_Bibliotecario');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePrestamo::class, 'Codigo_Devolucion', 'Codigo_Devolucion');
    }
}
