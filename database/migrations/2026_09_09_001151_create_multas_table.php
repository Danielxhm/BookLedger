<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('multas', function (Blueprint $table) {
            $table->increments('Codigo_Multa');
            $table->unsignedInteger('Codigo_Detalle');
            $table->decimal('Monto', 8, 2)->default(0.00);
            $table->enum('Motivo', ['Mora', 'Daño', 'Perdida'])->default('Mora');
            $table->enum('Estado_Pago', ['Pendiente', 'Pagado'])->default('Pendiente');
            $table->date('Fecha_Emision')->default(DB::raw('(CURRENT_DATE)'));
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
            $table->string('usuario_modificacion', 50)->nullable();
            $table->string('motivo_modificacion', 200)->nullable();

            $table->foreign('Codigo_Detalle')->references('Codigo_Detalle')->on('detalle_prestamos');
        });
    }
    public function down(): void { Schema::dropIfExists('multas'); }
};
