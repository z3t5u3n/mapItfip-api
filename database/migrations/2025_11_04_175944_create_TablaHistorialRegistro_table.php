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
        Schema::create('TablaHistorialRegistro', function (Blueprint $table) {
            $table->integer('Id', true);
            $table->string('IdUsuario', 30)->index('idusuario');
            $table->dateTime('Fecha_Sys')->useCurrent()->index('fecha_sys');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TablaHistorialRegistro');
    }
};
