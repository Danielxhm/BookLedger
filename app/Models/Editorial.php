<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Editorial extends Model
{
    protected $table = 'editorials';
    protected $primaryKey = 'Codigo_Editorial';
    public $timestamps = false;
    protected $fillable = ['NombreEditorial', 'Pais'];

    public function libros()
    {
        return $this->hasMany(Libro::class, 'Codigo_Editorial', 'Codigo_Editorial');
    }
}
