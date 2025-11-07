<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaRol extends Model
{
    use HasFactory;

    protected $table = 'TablaRol';
    protected $primaryKey = 'IdRol';
    public $timestamps = false;
    protected $fillable = ['TipoRol'];

    // AÑADIDO: Corrección de tipo para PostgreSQL (SMALLINT)
    protected $keyType = 'int'; 
    public $incrementing = true;

    public function usuarios()
    {
        return $this->hasMany(TablaUsuario::class, 'IdRol', 'IdRol');
    }
}
