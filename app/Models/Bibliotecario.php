<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Bibliotecario extends Model
{
    protected $table = 'bibliotecarios';
    protected $primaryKey = 'Codigo_Bibliotecario';
    public $timestamps = false;

    protected $fillable = [
        'Num_documento',
        'Nombres',
        'Apellidos',
        'NombreUsuario',
        'clave_hash',
        'EstadoEmpleado',
        'Codigo_Supervisor',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Bibliotecario::class, 'Codigo_Supervisor');
    }
}
