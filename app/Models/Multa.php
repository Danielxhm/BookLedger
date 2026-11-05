<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Multa extends Model
{
    protected $table = 'multas';
    protected $primaryKey = 'Codigo_Multa';
    public $timestamps = false;

    protected $fillable = [
        'Codigo_Detalle',
        'Monto',
        'Motivo',
        'Estado_Pago',
        'Fecha_Emision',
        'fecha_modificacion',
        'usuario_modificacion',
        'motivo_modificacion',
    ];

    protected $attributes = [
        'Motivo' => 'Mora',
        'Estado_Pago' => 'Pendiente',
    ];

    protected $casts = [
        'Monto' => 'decimal:2',
        'Fecha_Emision' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function (Multa $multa) {
            if (empty($multa->usuario_modificacion)) {
                $multa->usuario_modificacion = auth()->user()?->name ?? auth()->user()?->email ?? 'Sistema';
            }
            if (empty($multa->motivo_modificacion)) {
                $multa->motivo_modificacion = 'Alta de multa (' . ($multa->Motivo ?? 'Mora') . ')';
            }
            $multa->fecha_modificacion = now();
        });

        static::created(function (Multa $multa) {
            if ($multa->Estado_Pago === 'Pendiente') {
                $socio = $multa->detalle?->prestamo?->socio;
                if ($socio && $socio->EstadoSocio !== 'Sancionado') {
                    $socio->update(['EstadoSocio' => 'Sancionado']);
                }
            }
        });

        static::updating(function (Multa $multa) {
            if (empty($multa->usuario_modificacion)) {
                $multa->usuario_modificacion = auth()->user()?->name ?? auth()->user()?->email ?? $multa->getOriginal('usuario_modificacion') ?? 'Sistema';
            }
            $multa->fecha_modificacion = now();
        });

        static::updated(function (Multa $multa) {
            $socio = $multa->detalle?->prestamo?->socio;
            if ($socio) {
                if ($socio->EstadoSocio === 'Sancionado' && ! $socio->multasPendientes()->exists()) {
                    $socio->update(['EstadoSocio' => 'Activo']);
                } elseif ($socio->EstadoSocio === 'Activo' && $socio->multasPendientes()->exists()) {
                    $socio->update(['EstadoSocio' => 'Sancionado']);
                }
            }
        });

        static::deleted(function (Multa $multa) {
            $socio = $multa->detalle?->prestamo?->socio;
            if ($socio && $socio->EstadoSocio === 'Sancionado' && ! $socio->multasPendientes()->exists()) {
                $socio->update(['EstadoSocio' => 'Activo']);
            }
        });
    }

    public function detalle()
    {
        return $this->belongsTo(DetallePrestamo::class, 'Codigo_Detalle', 'Codigo_Detalle');
    }
}
