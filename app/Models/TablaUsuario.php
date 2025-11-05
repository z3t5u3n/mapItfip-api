<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TablaUsuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'TablaUsuario'; // Nombre de tu tabla
    protected $primaryKey = 'Id'; // La clave primaria de esta tabla
    public $timestamps = false; // Desactiva los timestamps si no los usas

    protected $fillable = [
        'IdUsuario',
        'Nombres',
        'Apellidos',
        'IdDocumento',
        'NumeroDocumento',
        'IdRol',
        'IdActivo',
        'Fecha_Sys',
        'activation_token',
        'activation_expires_at', // Añadir estas nuevas columnas
        'RolBloqueado',

    ];
    public function usuario()
    {
        return $this->belongsTo(TablaRegistroErroresMapa::class, 'IdUsuario', 'IdUsuario');
    }
    public function documento()
    {
        return $this->belongsTo(TablaDocumento::class, 'IdDocumento', 'IdDocumento');
    }

    public function rol()
    {
        return $this->belongsTo(TablaRol::class, 'IdRol', 'IdRol');
    }

    public function estudiante()
    {
        return $this->hasOne(TablaEstudiante::class, 'IdUsuario', 'IdUsuario');
    }

    public function profesor()
    {
        return $this->hasOne(TablaProfesor::class, 'IdUsuario', 'IdUsuario');
    }
    
    // ============================
    // 🧩 MÉTODOS AUXILIARES
    // ============================

    /**
     * Verifica si el rol del usuario ya está bloqueado.
     */
    public function rolEstaBloqueado(): bool
    {
        return $this->RolBloqueado == 1;
    }

    /**
     * Marca el rol como bloqueado.
     */
    public function bloquearRol(): void
    {
        $this->RolBloqueado = 1;
        $this->save();
    }

}