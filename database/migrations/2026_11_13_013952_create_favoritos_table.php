
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favoritos', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('Codigo_Socio');
            $table->string('Codigo_ISBN', 20);

            $table->timestamps();

            // Relación con socios
            $table->foreign('Codigo_Socio')
                ->references('Codigo_Socio')
                ->on('socios')
                ->onDelete('cascade');

            // Relación con libros
            $table->foreign('Codigo_ISBN')
                ->references('Codigo_ISBN')
                ->on('libros')
                ->onDelete('cascade');

            // Evitar libros duplicados en favoritos del mismo socio
            $table->unique(['Codigo_Socio', 'Codigo_ISBN']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favoritos');
    }
};
