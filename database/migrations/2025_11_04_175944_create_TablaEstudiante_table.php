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
        Schema::create('TablaEstudiante', function (Blueprint $table) {
            $table->integer('Id', true);
            $table->string('IdUsuario', 30)->index('idusuario');
            $table->tinyInteger('IdRol')->index('idrol');
            $table->tinyInteger('Activo')->default(1);
            $table->tinyInteger('IdCarrera')->index('idcarrera');
            $table->tinyInteger('IdSemestre')->index('idsemestre');
            $table->string('CorreoInstitucional', 60)->unique('correoinstitucional');
            $table->dateTime('Fecha_Sys')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TablaEstudiante');
    }
};
