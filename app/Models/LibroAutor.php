<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibroAutor extends Model
{
    protected $table = 'libro_autors';
    protected $primaryKey = 'Codigo_ISBN';
    public $timestamps = false;

    protected $fillable = [
        'Codigo_ISBN', 'Codigo_Autor', 'Tipo_Autoria',
        'fecha_modificacion', 'usuario_modificacion', 'motivo_modificacion'
    ];

    protected $attributes = [
        'Tipo_Autoria' => ['Principal', 'Coautor', 'Traductor'],
    ];

    public function libros()
    {
        return $this->belongsTo(Libro::class, 'Codigo_ISBN', 'Codigo_ISBN');
    }

    public function autores()
    {
        return $this->belongsTo(Autor::class, 'Codigo_Autor', 'Codigo_Autor');
    }
}
