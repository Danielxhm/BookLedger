<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void {
        Schema::create('categorias', function (Blueprint $table) {
            $table->increments('Codigo_Categoria');
            $table->string('NombreCategoria', 50);
            $table->string('Descripcion', 200)->nullable();
            $table->unsignedInteger('Codigo_categoriaPadre')->nullable();
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
            $table->string('usuario_modificacion', 50)->nullable();
            $table->string('motivo_modificacion', 200)->nullable();

            $table->foreign('Codigo_categoriaPadre')->references('Codigo_Categoria')->on('categorias');
        });
    }
    public function down(): void { Schema::dropIfExists('categorias'); }
};
