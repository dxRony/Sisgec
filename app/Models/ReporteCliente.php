<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteCliente extends Model
{
    protected $table = 'ReporteCliente';
    public $timestamps = false;

    protected $fillable = [
        'tipo',
        'idEmpleado',
        'idComponente',
        'idComputadora',
        'descripcion',
        'fecha',
        'nitUsuario'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'nitUsuario', 'id');
    }

    public function empleado()
    {
        return $this->belongsTo(User::class, 'idEmpleado', 'id');
    }

    public function componente()
    {
        return $this->belongsTo(Componente::class, 'idComponente', 'id');
    }

    public function computadora()
    {
        return $this->belongsTo(Computadora::class, 'idComputadora', 'id');
    }
}
