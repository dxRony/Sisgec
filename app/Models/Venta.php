<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Venta extends Model
{
    protected $table = 'Venta';
    protected $fillable = ['total', 'nitUsuario', 'nitEmpleado'];

    // Cliente
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'nitUsuario');
    }

    // Empleado
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'nitEmpleado');
    }

    // Relación con DetalleVenta
    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'idVenta');
    }

    // Relación con Factura
    public function factura(): HasOne
    {
        return $this->hasOne(Factura::class, 'idVenta');
    }
}
