<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';
    protected $primaryKey = 'Codigo_Reserva';
    public $timestamps = false;

    protected $fillable = [
        'Codigo_Socio',
        'Codigo_ISBN',
        'FechaReserva',
        'EstadoReserva',
        'fecha_modificacion',
        'usuario_modificacion',
        'motivo_modificacion',
    ];

    protected $casts = [
        'FechaReserva' => 'date',
    ];

    protected $appends = [
        'Fecha_Reserva',
        'Estado',
    ];

    public function getFechaReservaAttribute(): ?string
    {
        return isset($this->attributes['FechaReserva'])
            ? \Carbon\Carbon::parse($this->attributes['FechaReserva'])->format('d/m/Y')
            : null;
    }

    public function getEstadoAttribute(): string
    {
        return $this->attributes['EstadoReserva'] ?? 'Pendiente';
    }

    public function socio()
    {
        return $this->belongsTo(Socio::class, 'Codigo_Socio', 'Codigo_Socio');
    }

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'Codigo_ISBN', 'Codigo_ISBN');
    }
}

