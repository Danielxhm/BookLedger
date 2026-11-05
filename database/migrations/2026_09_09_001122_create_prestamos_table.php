<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->increments('Codigo_Prestamo');
            $table->unsignedInteger('Codigo_Socio');
            $table->unsignedInteger('Codigo_Bibliotecario');
            $table->enum('EstadoPrestamo', ['Activo', 'Concluido', 'Vencido'])->default('Activo');
            $table->date('FechaPrestamo')->default(DB::raw('(CURRENT_DATE)'));
            $table->date('FechaLimite_Devolucion');
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
            $table->string('usuario_modificacion', 50)->nullable();
            $table->string('motivo_modificacion', 200)->nullable();

            $table->foreign('Codigo_Bibliotecario')->references('Codigo_Bibliotecario')
                ->on('bibliotecarios')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('Codigo_Socio')->references('Codigo_Socio')
                ->on('socios')->restrictOnDelete()->cascadeOnUpdate();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
