<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'Codigo_Categoria';
    public $timestamps = false;
    protected $fillable = ['NombreCategoria', 'Descripcion', 'Codigo_categoriaPadre'];

    public function padre()
    {
        return $this->belongsTo(Categoria::class, 'Codigo_categoriaPadre', 'Codigo_Categoria');
    }

    public function subcategorias()
    {
        return $this->hasMany(Categoria::class, 'Codigo_categoriaPadre', 'Codigo_Categoria');
    }

    public function libros()
    {
        return $this->hasMany(Libro::class, 'Codigo_Categoria', 'Codigo_Categoria');
    }
}
