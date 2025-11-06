<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaDocumento extends Model
{
    use HasFactory;

    protected $table = 'TablaDocumento'; // Nombre de la tabla
    protected $primaryKey = 'IdDocumento'; // Clave primaria de la tabla
    public $timestamps = false; // Si tu tabla no tiene columnas created_at y updated_at
    public function estudiantes()
    {
        return $this->hasMany(TablaEstudiante::class, 'IdDocumento', 'IdDocumento');
    }
}
