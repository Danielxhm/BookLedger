<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('libros', function (Blueprint $table) {
            $table->string('Codigo_ISBN', 20)->primary();
            $table->string('Titulo', 150);
            $table->string('Subtitulo', 150)->nullable();
            $table->integer('Anio_Publicacion');
            $table->integer('N_paginas');
            $table->string('Idioma', 30)->default('español');
            $table->unsignedInteger('Codigo_Categoria');
            $table->unsignedInteger('Codigo_Editorial');
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
            $table->string('usuario_modificacion', 50)->nullable();
            $table->string('motivo_modificacion', 200)->nullable();

            $table->foreign('Codigo_Categoria')->references('Codigo_Categoria')->on('categorias');
            $table->foreign('Codigo_Editorial')->references('Codigo_Editorial')->on('editorials');
        });
    }
    public function down(): void { Schema::dropIfExists('libros'); }
};
