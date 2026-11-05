<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('autors', function (Blueprint $table) {
            $table->increments('Codigo_Autor');
            $table->string('Nombres', 50);
            $table->string('Apellidos', 50);
            $table->string('Seudonimo', 50)->nullable();
            $table->text('Biografia')->nullable();
            $table->string('Nacionalidad', 50);
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
            $table->string('usuario_modificacion', 50)->nullable();
            $table->string('motivo_modificacion', 200)->nullable();
        });
    }
    public function down(): void { Schema::dropIfExists('autors'); }
};
