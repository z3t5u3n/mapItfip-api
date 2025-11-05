<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaAdministrador extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'TablaAdministrador';

    /**
     * La clave primaria de la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'IdAdmin';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'IdAdmin',
        'NumeroDocumento',
        'IdDocumento',
    ];

    /**
     * Indica si el modelo debe ser timestamped.
     * No hay 'created_at' y 'updated_at' en tu esquema.
     *
     * @var bool
     */
    public $timestamps = false;
}