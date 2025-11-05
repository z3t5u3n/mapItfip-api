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
        Schema::create('TablaUsuario', function (Blueprint $table) {
            $table->integer('Id', true);
            $table->string('IdUsuario', 30)->unique('idusuario');
            $table->string('Nombres', 100);
            $table->string('Apellidos', 100);
            $table->tinyInteger('IdDocumento')->index('iddocumento');
            $table->integer('NumeroDocumento')->unique('numerodocumento');
            $table->tinyInteger('IdRol')->index('idrol');
            $table->tinyInteger('IdActivo')->default(1)->index('idactivo');
            $table->dateTime('Fecha_Sys')->useCurrent();
            $table->string('activation_token', 100)->nullable()->unique('activation_token');
            $table->timestamp('activation_expires_at')->nullable();
            $table->boolean('RolBloqueado')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TablaUsuario');
    }
};
