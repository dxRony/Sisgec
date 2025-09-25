<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleVenta extends Model
{
    protected $table = 'DetalleVenta';
    protected $fillable = ['idVenta', 'idComputadora', 'idComponente', 'cantidad', 'subtotal'];
    public $timestamps = false;

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'idVenta');
    }

    public function computadora(): BelongsTo
    {
        return $this->belongsTo(Computadora::class, 'idComputadora');
    }

    public function componente(): BelongsTo
    {
        return $this->belongsTo(Componente::class, 'idComponente');
    }
}
