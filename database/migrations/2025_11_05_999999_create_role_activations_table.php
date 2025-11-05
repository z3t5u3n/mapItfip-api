<?php
// database/migrations/YYYY_MM_DD_HHMMSS_create_role_activations_table.php

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
        Schema::create('role_activations', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); // Asegúrate de que coincida con el tipo de IdUsuario en TablaUsuario
            $table->unsignedBigInteger('new_role_id'); // Para el nuevo rol (ej. 2 para estudiante, 3 para profesor)
            $table->string('token')->unique(); // El token único de activación
            $table->timestamp('expires_at'); // Fecha de expiración del token
            $table->json('role_data')->nullable(); // Para guardar los datos adicionales del rol (semestre, carrera, etc.)
            $table->timestamps();

            $table->foreign('user_id')->references('IdUsuario')->on('TablaUsuario')->onDelete('cascade');
            $table->foreign('new_role_id')->references('IdRol')->on('TablaRol')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_activations');
    }
};
