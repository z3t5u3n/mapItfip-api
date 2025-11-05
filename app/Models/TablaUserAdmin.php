<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaUserAdmin extends Model
{
    use HasFactory;

    protected $table = 'TablaRoot';

    protected $primaryKey = 'Id'; 

    protected $fillable = ['Nombre', 'Apellidos', 'Contraseña', 'CorreoIntitucional', 'Activo', 'Fecha_Sys'];
}
