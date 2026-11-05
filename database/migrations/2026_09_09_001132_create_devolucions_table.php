<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('devolucions', function (Blueprint $table) {
            $table->increments('Codigo_Devolucion');
            $table->unsignedInteger('Codigo_Bibliotecario');
            $table->date('Fecha_Devolucion')->default(DB::raw('(CURRENT_DATE)'));
            $table->text('Observaciones')->nullable();
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
            $table->string('usuario_modificacion', 50)->nullable();
            $table->string('motivo_modificacion', 200)->nullable();

            $table->foreign('Codigo_Bibliotecario')->references('Codigo_Bibliotecario')
                ->on('bibliotecarios')->restrictOnDelete()->cascadeOnUpdate();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('devolucions');
    }
};
