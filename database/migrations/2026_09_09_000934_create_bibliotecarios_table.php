<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bibliotecarios', function (Blueprint $table) {
            $table->increments('Codigo_Bibliotecario');
            $table->string('Num_documento', 20);
            $table->string('Nombres', 50);
            $table->string('Apellidos', 50);
            $table->string('NombreUsuario', 30)->unique();
            $table->string('clave_hash', 255);
            $table->enum('EstadoEmpleado', ['Activo', 'Inactivo'])->default('Activo');
            $table->unsignedInteger('Codigo_Supervisor')->nullable();
            $table->foreignId('user_id')->nullable()->unique()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();

            // columnas que necesita Jetstream/Fortify para que el login funcione
            $table->rememberToken();
            $table->string('two_factor_secret', 255)->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();

            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
            $table->string('usuario_modificacion', 50)->nullable();
            $table->string('motivo_modificacion', 200)->nullable();

            $table->foreign('Codigo_Supervisor')->references('Codigo_Bibliotecario')->on('bibliotecarios')->onDelete('set null')->onUpdate('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('bibliotecarios');
    }
};
