<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaProfesor extends Model
{
    use HasFactory;

    protected $table = 'TablaProfesor';
    protected $primaryKey = 'IdUsuario';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'IdUsuario', 'IdRol', 'Activo', 'CorreoInstitucional', 'Fecha_Sys'
    ];

    // Relación con TablaUsuario
    public function usuario()
    {
        return $this->belongsTo(TablaUsuario::class, 'IdUsuario', 'IdUsuario');
    }
}