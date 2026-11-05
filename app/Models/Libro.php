<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = 'libros';
    protected $primaryKey = 'Codigo_ISBN';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'Codigo_ISBN',
        'Titulo',
        'Subtitulo',
        'Anio_Publicacion',
        'N_paginas',
        'Idioma',
        'Codigo_Categoria',
        'Codigo_Editorial',
        'portada',
        'fecha_modificacion',
        'usuario_modificacion',
        'motivo_modificacion'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'Codigo_Categoria', 'Codigo_Categoria');
    }

    public function editorial()
    {
        return $this->belongsTo(Editorial::class, 'Codigo_Editorial', 'Codigo_Editorial');
    }

    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'libro_autors', 'Codigo_ISBN', 'Codigo_Autor')
            ->withPivot('Tipo_Autoria');
    }

    public function libroAutores()
    {
        return $this->hasMany(LibroAutor::class, 'Codigo_ISBN', 'Codigo_ISBN');
    }

    public function ejemplares()
    {
        return $this->hasMany(Ejemplar::class, 'Codigo_ISBN', 'Codigo_ISBN');
    }

    
}