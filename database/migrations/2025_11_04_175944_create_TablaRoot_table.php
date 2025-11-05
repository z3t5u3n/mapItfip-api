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
        Schema::create('TablaRoot', function (Blueprint $table) {
            $table->integer('Id', true);
            $table->string('Nombre', 100);
            $table->string('Apellidos', 100);
            $table->string('Contraseña');
            $table->string('CorreoInstitucional', 60)->unique('correoinstitucional');
            $table->tinyInteger('Activo')->default(1);
            $table->dateTime('Fecha_Sys')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TablaRoot');
    }
};
