<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ensamblaje extends Model
{
    protected $table = 'Ensamblaje';
    protected $fillable = ['idComputadora', 'idEmpleado', 'estado', 'fecha'];

    // Relación con Computadora
    public function computadora(): BelongsTo
    {
        return $this->belongsTo(Computadora::class, 'idComputadora');
    }

    // Relación con Usuario (empleado)
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idEmpleado');
    }

    // Relación con DetalleEnsamblaje
    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleEnsamblaje::class, 'idEnsamblaje');
    }
}
