<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaRegistroErroresMapa extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'TablaRegistroErroresMapa';

    // Deshabilitamos timestamps automáticos
    public $timestamps = false;
    protected $primaryKey = 'Id'; // 👈 No hay Primary Key
    public $incrementing = true; // 👈 No autoincrement
    // Campos que se pueden insertar
    protected $fillable = [
        'IdUsuario',
        'Error',
        'Aspecto',
        'Punto',
        'Fecha_Sys'
    ];

    public function usuario()
    {
        return $this->belongsTo(TablaUsuario::class, 'IdUsuario', 'IdUsuario');
    }
}
