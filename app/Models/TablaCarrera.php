<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaCarrera extends Model
{
    use HasFactory;

    protected $table = 'TablaCarrera';
    protected $primaryKey = 'IdCarrera';
    public $timestamps = false;
    protected $fillable = ['Carrera'];

    // AÑADIDO: Corrección de tipo para PostgreSQL (SMALLINT)
    protected $keyType = 'int'; 
    public $incrementing = true;
    
    public function estudiantes()
    {
        return $this->hasMany(TablaEstudiante::class, 'IdCarrera', 'IdCarrera');
    }
}
