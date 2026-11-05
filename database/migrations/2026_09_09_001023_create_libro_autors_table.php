<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('libro_autors', function (Blueprint $table) {
            $table->string('Codigo_ISBN', 20);
            $table->unsignedInteger('Codigo_Autor');
            $table->enum('Tipo_Autoria', ['Principal', 'Coautor', 'Traductor'])->default('Principal');
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
            $table->string('usuario_modificacion', 50)->nullable();
            $table->string('motivo_modificacion', 200)->nullable();

            $table->primary(['Codigo_ISBN', 'Codigo_Autor']);
            $table->foreign('Codigo_ISBN')->references('Codigo_ISBN')->on('libros')->onDelete('cascade');
            $table->foreign('Codigo_Autor')->references('Codigo_Autor')->on('autors')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('libro_autors'); }
};
