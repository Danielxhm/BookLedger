<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('socios', function (Blueprint $table) {
            $table->increments('Codigo_Socio');
            $table->string('Num_Documento', 20)->unique();
            $table->string('Nombres', 50);
            $table->string('Apellidos', 50);
            $table->string('Email', 100)->unique();
            $table->string('Telefono', 15);
            $table->enum('EstadoSocio', ['Activo', 'Sancionado', 'Inactivo'])->default('Activo');
            $table->date('Fecha_Nac');
            $table->date('Fecha_RegistroSocio')->default(DB::raw('(CURRENT_DATE)'));
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
            $table->string('usuario_modificacion', 50)->nullable();
            $table->string('motivo_modificacion', 200)->nullable();
        });
    }
    public function down(): void { Schema::dropIfExists('socios'); }
};
