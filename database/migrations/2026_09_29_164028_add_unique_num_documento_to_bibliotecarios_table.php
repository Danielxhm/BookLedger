<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('bibliotecarios', function (Blueprint $table) {
        $table->unique('Num_documento');
    });
}

public function down(): void
{
    Schema::table('bibliotecarios', function (Blueprint $table) {
        $table->dropUnique(['Num_documento']);
    });
}
};
