<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaHistorialRegistro extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'TablaHistorialRegistro';

    /**
     * La clave primaria de la tabla.
     * Según tu esquema, esta tabla no tiene una clave primaria autoincremental definida explícitamente.
     * Si no hay una columna 'id' autoincremental, debes establecer $primaryKey a null.
     *
     * @var string|null
     */
    protected $primaryKey = null; // No tiene clave primaria autoincremental

    /**
     * Indica si el ID es autoincremental.
     * Como no hay una PK autoincremental, se establece en false.
     *
     * @var bool
     */
    public $incrementing = false; // No es autoincremental

    /**
     * Indica si el modelo debe ser timestamped.
     * Por defecto es true (crea 'created_at' y 'updated_at').
     * Tu tabla tiene 'Fecha_Sys', por lo que no necesitas las columnas por defecto de Laravel.
     *
     * @var bool
     */
    public $timestamps = false; // No usa 'created_at' y 'updated_at' por defecto de Laravel

    /**
     * Los atributos que son asignables masivamente.
     * Esto permite asignar valores a estas columnas usando el método create() o fill().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'IdUsuario', // El ID del usuario que inicia sesión
        'Fecha_Sys', // La fecha y hora del inicio de sesión
    ];

    /**
     * Los atributos que deberían ser casteados a tipos nativos.
     * Útil para fechas, JSON, booleanos, etc.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'Fecha_Sys' => 'datetime', // Asegura que 'Fecha_Sys' se maneje como un objeto DateTime
    ];

    // Relaciones (opcional, pero buena práctica si las necesitas)

    /**
     * Relación con el modelo TablaUsuario.
     * Un registro de historial pertenece a un usuario.
     */
    public function usuario()
    {
        // Asume que 'IdUsuario' en TablaHistorialRegistro es la clave foránea
        // y se relaciona con 'IdUsuario' en TablaUsuario.
        return $this->belongsTo(TablaUsuario::class, 'IdUsuario', 'IdUsuario');
    }
}
