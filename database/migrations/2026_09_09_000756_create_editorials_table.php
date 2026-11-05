<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('editorials', function (Blueprint $table) {
            $table->increments('Codigo_Editorial');
            $table->string('NombreEditorial', 100);
            $table->string('Pais', 50);
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
            $table->string('usuario_modificacion', 50)->nullable();
            $table->string('motivo_modificacion', 200)->nullable();
        });
    }
    public function down(): void { Schema::dropIfExists('editorials'); }
};
