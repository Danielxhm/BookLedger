<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('reservas', function (Blueprint $table) {
            $table->increments('Codigo_Reserva');
            $table->unsignedInteger('Codigo_Socio');
            $table->string('Codigo_ISBN', 20);
            $table->date('FechaReserva')->default(DB::raw('(CURRENT_DATE)'));
            $table->enum('EstadoReserva', ['Pendiente', 'Atendida', 'Cancelada'])->default('Pendiente');
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
            $table->string('usuario_modificacion', 50)->nullable();
            $table->string('motivo_modificacion', 200)->nullable();

            $table->foreign('Codigo_Socio')->references('Codigo_Socio')->on('socios');
            $table->foreign('Codigo_ISBN')->references('Codigo_ISBN')->on('libros');
        });
    }
    public function down(): void { Schema::dropIfExists('reservas'); }
};
