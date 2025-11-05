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
        // 1. Claves foráneas para TablaUsuario
        Schema::table('TablaUsuario', function (Blueprint $table) {
            // FK: IdRol -> TablaRol
            $table->foreign('IdRol')->references('IdRol')->on('TablaRol');
            // FK: IdDocumento -> TablaDocumento
            $table->foreign('IdDocumento')->references('IdDocumento')->on('TablaDocumento');
        });

        // 2. Claves foráneas para TablaEstudiante
        Schema::table('TablaEstudiante', function (Blueprint $table) {
            // FK: IdUsuario -> TablaUsuario (ON DELETE CASCADE)
            $table->foreign('IdUsuario')->references('IdUsuario')->on('TablaUsuario')->onDelete('cascade');
            // FK: IdRol -> TablaRol
            $table->foreign('IdRol')->references('IdRol')->on('TablaRol');
            // FK: IdCarrera -> TablaCarrera
            $table->foreign('IdCarrera')->references('IdCarrera')->on('TablaCarrera');
            // FK: IdSemestre -> TablaSemestre
            $table->foreign('IdSemestre')->references('IdSemestre')->on('TablaSemestre');
        });

        // 3. Claves foráneas para TablaProfesor
        Schema::table('TablaProfesor', function (Blueprint $table) {
            // FK: IdUsuario -> TablaUsuario (ON DELETE CASCADE)
            $table->foreign('IdUsuario')->references('IdUsuario')->on('TablaUsuario')->onDelete('cascade');
            // FK: IdRol -> TablaRol
            $table->foreign('IdRol')->references('IdRol')->on('TablaRol');
        });

        // 4. Claves foráneas para Tablas de Historial y Errores
        Schema::table('TablaHistorialRegistro', function (Blueprint $table) {
            // FK: IdUsuario -> TablaUsuario (ON DELETE CASCADE)
            $table->foreign('IdUsuario')->references('IdUsuario')->on('TablaUsuario')->onDelete('cascade');
        });

        Schema::table('TablaHistorialUsoMapa', function (Blueprint $table) {
            // FK: IdUsuario -> TablaUsuario (ON DELETE CASCADE)
            $table->foreign('IdUsuario')->references('IdUsuario')->on('TablaUsuario')->onDelete('cascade');
        });

        Schema::table('TablaRegistroErroresMapa', function (Blueprint $table) {
            // FK: IdUsuario -> TablaUsuario
            $table->foreign('IdUsuario')->references('IdUsuario')->on('TablaUsuario');
        });

        // 5. Claves foráneas para role_activations
        Schema::table('role_activations', function (Blueprint $table) {
            // FK: user_id -> TablaUsuario (asumiendo que user_id es el mismo tipo que TablaUsuario.IdUsuario)
            $table->foreign('user_id')->references('IdUsuario')->on('TablaUsuario')->onDelete('cascade');
            // FK: new_role_id -> TablaRol
            $table->foreign('new_role_id')->references('IdRol')->on('TablaRol')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // El down es CRÍTICO para el rollback. Borrar las claves foráneas en orden inverso.
        
        Schema::table('role_activations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['new_role_id']);
        });

        Schema::table('TablaRegistroErroresMapa', function (Blueprint $table) {
            $table->dropForeign(['IdUsuario']);
        });
        
        Schema::table('TablaHistorialUsoMapa', function (Blueprint $table) {
            $table->dropForeign(['IdUsuario']);
        });

        Schema::table('TablaHistorialRegistro', function (Blueprint $table) {
            $table->dropForeign(['IdUsuario']);
        });

        Schema::table('TablaProfesor', function (Blueprint $table) {
            $table->dropForeign(['IdUsuario']);
            $table->dropForeign(['IdRol']);
        });

        Schema::table('TablaEstudiante', function (Blueprint $table) {
            $table->dropForeign(['IdUsuario']);
            $table->dropForeign(['IdRol']);
            $table->dropForeign(['IdCarrera']);
            $table->dropForeign(['IdSemestre']);
        });
        
        Schema::table('TablaUsuario', function (Blueprint $table) {
            $table->dropForeign(['IdRol']);
            $table->dropForeign(['IdDocumento']);
        });
    }
};