<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Factura extends Model
{
    protected $table = 'Factura';
    protected $fillable = ['idVenta', 'nit', 'fecha'];

    // Relación con Venta
    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'idVenta');
    }
}
