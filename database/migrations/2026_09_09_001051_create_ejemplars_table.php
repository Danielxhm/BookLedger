<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('ejemplars', function (Blueprint $table) {
            $table->increments('Codigo_Ejemplar');
            $table->string('Codigo_ISBN', 20);
            $table->string('Ubicacion_Estante', 30);
            $table->enum('Estado_Ejemplar', ['Disponible', 'Prestado', 'Mantenimiento', 'Perdido'])->default('Disponible');
            $table->date('Fecha_Adquisicion')->default(DB::raw('(CURRENT_DATE)'));
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
            $table->string('usuario_modificacion', 50)->nullable();
            $table->string('motivo_modificacion', 200)->nullable();

            $table->foreign('Codigo_ISBN')->references('Codigo_ISBN')->on('libros');
        });
    }
    public function down(): void { Schema::dropIfExists('ejemplars'); }
};
