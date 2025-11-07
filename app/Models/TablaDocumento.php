<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaDocumento extends Model
{
    use HasFactory;

    protected $table = 'TablaDocumento';
    protected $primaryKey = 'IdDocumento';
    public $timestamps = false;
    
    // ✅ CORRECCIÓN CRÍTICA 1: Define el tipo de la clave primaria como entero, no string
    protected $keyType = 'int'; 
    
    // ✅ CORRECCIÓN CRÍTICA 2: Confirma que la clave se incrementa (es lo que espera PostgreSQL)
    public $incrementing = true;
    
    // ✅ BUENA PRÁCTICA: Asegúrate de que las columnas están definidas
    // Rellena con las columnas que realmente tiene tu tabla, por ejemplo:
    protected $fillable = ['TipoDocumento']; 
}
