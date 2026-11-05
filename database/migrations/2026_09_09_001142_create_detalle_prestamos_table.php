<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('detalle_prestamos', function (Blueprint $table) {
            $table->increments('Codigo_Detalle');
            $table->unsignedInteger('Codigo_Prestamo');
            $table->unsignedInteger('Codigo_Ejemplar');
            $table->enum('EstadoEntrega', ['Buen estado', 'Dañado', 'Perdido'])->nullable();
            $table->unsignedInteger('Codigo_Devolucion')->nullable();
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
            $table->string('usuario_modificacion', 50)->nullable();
            $table->string('motivo_modificacion', 200)->nullable();

            $table->foreign('Codigo_Prestamo')->references('Codigo_Prestamo')->on('prestamos');
            $table->foreign('Codigo_Ejemplar')->references('Codigo_Ejemplar')->on('ejemplars');
            $table->foreign('Codigo_Devolucion')->references('Codigo_Devolucion')->on('devolucions');
        });
    }
    public function down(): void { Schema::dropIfExists('detalle_prestamos'); }
};
