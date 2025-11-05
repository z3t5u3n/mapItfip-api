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
            $table->increments('Id'); // Clave primaria autoincremental
            $table->string('IdUsuario', 10)->unique(); // Campo único para el ID de usuario (¿generado o ingresado?)
            $table->text('Nombre');
            $table->text('Apellidos'); // Asumo que también necesitas apellidos
            $table->tinyInteger('IdDocumento'); // Clave foránea a TablaDocumento
            $table->integer('NumeroDocumento');
            $table->tinyInteger('IdRol'); // Clave foránea a TablaRol
            $table->tinyInteger('IdActivo')->default(1); // 1 = activo (true), 0 = inactivo (false)
            $table->dateTime('Fecha_Sys')->default(DB::raw('CURRENT_TIMESTAMP')); // Fecha de creación
            // Si Laravel no maneja los timestamps automáticamente, no incluyas $table->timestamps();
            // Si la columna Id es INT(11) y AUTO_INCREMENT, 'increments' es lo correcto.
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