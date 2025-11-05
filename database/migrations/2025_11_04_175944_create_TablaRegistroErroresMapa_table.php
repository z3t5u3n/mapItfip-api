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
        Schema::create('TablaRegistroErroresMapa', function (Blueprint $table) {
            $table->integer('Id', true);
            $table->string('IdUsuario', 30)->index('idusuario');
            $table->text('Error');
            $table->text('Aspecto');
            $table->text('Punto');
            $table->dateTime('Fecha_Sys');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TablaRegistroErroresMapa');
    }
};
