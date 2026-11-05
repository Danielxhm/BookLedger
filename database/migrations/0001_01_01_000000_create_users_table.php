<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void {
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();

        // columnas del diccionario (SGB_Bibliotecario)
        $table->string('Num_documento', 20)->nullable();
        $table->string('Apellidos', 50)->nullable();
        $table->string('NombreUsuario', 30)->unique()->nullable();
        $table->enum('EstadoEmpleado', ['Activo', 'Inactivo'])->default('Activo');
        $table->foreignId('Codigo_Supervisor')->nullable()->constrained('users')->nullOnDelete();
    });

    
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
