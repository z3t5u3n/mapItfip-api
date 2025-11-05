<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaCarrera extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'TablaCarrera'; // Define el nombre de la tabla

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'IdCarrera'; // Define la clave primaria de la tabla

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false; // Desactiva los timestamps (created_at, updated_at) ya que no los tienes en esta tabla

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Carrera', // Campo que se puede asignar masivamente
    ];

    // Si tuvieras relaciones con otras tablas, irían aquí.
    // Por ejemplo, si una Carrera tiene muchos Estudiantes:
    /*
    public function estudiantes()
    {
        return $this->hasMany(TablaEstudiante::class, 'IdCarrera', 'IdCarrera');
    }
    */
}