<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaSemestre extends Model
{
    use HasFactory;

    protected $table = 'TablaSemestre';
    protected $primaryKey = 'IdSemestre';
    public $timestamps = false;
    protected $fillable = ['Semestre'];

    // AÑADIDO: Corrección de tipo para PostgreSQL (SMALLINT)
    protected $keyType = 'int'; 
    public $incrementing = true;
}
