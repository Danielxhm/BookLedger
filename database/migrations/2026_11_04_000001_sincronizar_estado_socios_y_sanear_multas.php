<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Socio;
use App\Models\Multa;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Normalizar montos que excedan los límites establecidos
        DB::table('multas')
            ->where('Motivo', 'Daño')
            ->where('Monto', '>', 2.00)
            ->update([
                'Monto' => 2.00,
                'motivo_modificacion' => 'Ajuste al límite establecido por daño ($2.00)',
                'fecha_modificacion' => now(),
            ]);

        DB::table('multas')
            ->where('Motivo', 'Perdida')
            ->where('Monto', '>', 10.00)
            ->update([
                'Monto' => 10.00,
                'motivo_modificacion' => 'Ajuste al límite establecido por pérdida ($10.00)',
                'fecha_modificacion' => now(),
            ]);

        DB::table('multas')
            ->where('Motivo', 'Mora')
            ->where('Monto', '>', 50.00)
            ->update([
                'Monto' => 50.00,
                'motivo_modificacion' => 'Ajuste al límite máximo por mora ($50.00)',
                'fecha_modificacion' => now(),
            ]);

        // 2. Registrar trazabilidad para multas anteriores
        DB::table('multas')
            ->whereNull('usuario_modificacion')
            ->update([
                'usuario_modificacion' => 'Sistema',
                'motivo_modificacion' => DB::raw("COALESCE(motivo_modificacion, 'Registro histórico de multa')"),
                'fecha_modificacion' => now(),
            ]);

        // 3. Sincronizar estado del socio según existencia de multas pendientes
        // Socios con multas pendientes que siguen Activos -> Sancionado
        DB::statement("
            UPDATE socios
            SET EstadoSocio = 'Sancionado'
            WHERE EstadoSocio != 'Sancionado'
              AND Codigo_Socio IN (
                  SELECT p.Codigo_Socio
                  FROM prestamos p
                  JOIN detalle_prestamos dp ON p.Codigo_Prestamo = dp.Codigo_Prestamo
                  JOIN multas m ON dp.Codigo_Detalle = m.Codigo_Detalle
                  WHERE m.Estado_Pago = 'Pendiente'
              )
        ");

        // Socios Sancionados sin multas pendientes -> Activo
        DB::statement("
            UPDATE socios
            SET EstadoSocio = 'Activo'
            WHERE EstadoSocio = 'Sancionado'
              AND Codigo_Socio NOT IN (
                  SELECT p.Codigo_Socio
                  FROM prestamos p
                  JOIN detalle_prestamos dp ON p.Codigo_Prestamo = dp.Codigo_Prestamo
                  JOIN multas m ON dp.Codigo_Detalle = m.Codigo_Detalle
                  WHERE m.Estado_Pago = 'Pendiente'
              )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No destructivo
    }
};
