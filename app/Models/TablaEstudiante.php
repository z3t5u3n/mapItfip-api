<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaEstudiante extends Model
{
    use HasFactory;

    protected $table = 'TablaEstudiante';
    protected $primaryKey = 'IdUsuario'; // La clave primaria de esta tabla
    public $incrementing = false; // Indica que IdUsuario no es auto-incrementable
    protected $keyType = 'string'; // Indica que la clave primaria es de tipo string
    public $timestamps = false;

    protected $fillable = [
        'IdUsuario', 'IdRol', 'Activo', 'IdCarrera', 'IdSemestre', 'CorreoInstitucional', 'Fecha_Sys'
    ];

    // Relación con TablaUsuario
    public function usuario()
    {
        return $this->belongsTo(TablaUsuario::class, 'IdUsuario', 'IdUsuario');
    }
}