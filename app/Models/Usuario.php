<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'Usuario';
    protected $primaryKey = 'nit';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'nit',
        'nombre',
        'username',
        'password',
        'celular',
        'activo',
        'rol'
    ];

    protected $hidden = [
        'password'
    ];

    // Relaciones
    public function ventasComoCliente(): HasMany
    {
        return $this->hasMany(Venta::class, 'nitUsuario');
    }

    public function ventasComoEmpleado(): HasMany
    {
        return $this->hasMany(Venta::class, 'nitEmpleado');
    }

    public function ensamblajes(): HasMany
    {
        return $this->hasMany(Ensamblaje::class, 'idEmpleado');
    }
}
