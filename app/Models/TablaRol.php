<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaRol extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'TablaRol'; // Define el nombre de la tabla

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'IdRol'; // Define la clave primaria de la tabla

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false; // Desactiva los timestamps (created_at, updated_at)

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'TipoRol', // Campo que se puede asignar masivamente
    ];

    // Si tuvieras relaciones con otras tablas, irían aquí.
    // Por ejemplo, si un Rol tiene muchos Usuarios:
    
    public function usuarios()
    {
        return $this->hasMany(TablaUsuario::class, 'IdRol', 'IdRol');
    }
    
}