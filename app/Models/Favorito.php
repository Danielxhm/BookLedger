<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorito extends Model
{
    protected $table = 'favoritos';

    protected $fillable = [
        'Codigo_Socio',
        'Codigo_ISBN',
    ];

    public function socio()
    {
        return $this->belongsTo(
            Socio::class,
            'Codigo_Socio',
            'Codigo_Socio'
        );
    }

    public function libro()
    {
        return $this->belongsTo(
            Libro::class,
            'Codigo_ISBN',
            'Codigo_ISBN'
        );
    }
}
