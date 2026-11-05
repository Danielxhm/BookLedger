<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Autor extends Model
{
    protected $table = 'autors';
    protected $primaryKey = 'Codigo_Autor';
    public $timestamps = false;
    protected $fillable = ['Nombres', 'Apellidos', 'Seudonimo', 'Biografia', 'Nacionalidad'];

    public function libros()
    {
        return $this->belongsToMany(Libro::class, 'libro_autors', 'Codigo_Autor', 'Codigo_ISBN')
            ->withPivot('Tipo_Autoria');
    }

    public function libroAutores()
    {
        return $this->hasMany(LibroAutor::class, 'Codigo_Autor', 'Codigo_Autor');
    }
}