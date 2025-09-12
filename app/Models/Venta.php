<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Venta extends Model
{
    protected $table = 'Venta';
    protected $fillable = ['total', 'nitUsuario', 'nitEmpleado', 'estado'];
    public $timestamps = false;

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nitUsuario');
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nitEmpleado');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'idVenta');
    }

    public function factura(): HasOne
    {
        return $this->hasOne(Factura::class, 'idVenta');
    }

    public function ensamblajes(): HasMany
    {
        return $this->hasMany(Ensamblaje::class, 'idVenta');
    }
}
