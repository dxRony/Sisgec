<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleVenta extends Model
{
    protected $table = 'DetalleVenta';
    protected $fillable = ['idVenta', 'idComputadora', 'idComponente', 'cantidad', 'subtotal'];
    public $timestamps = false;

    // Relación con Venta
    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'idVenta');
    }

    // Relación con Computadora
    public function computadora(): BelongsTo
    {
        return $this->belongsTo(Computadora::class, 'idComputadora');
    }

    // Relación con Componente
    public function componente(): BelongsTo
    {
        return $this->belongsTo(Componente::class, 'idComponente');
    }
}
