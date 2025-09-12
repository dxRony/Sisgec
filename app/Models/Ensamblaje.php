<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ensamblaje extends Model
{
    protected $table = 'Ensamblaje';
    protected $fillable = ['idVenta', 'idComputadora', 'idEmpleado', 'estado', 'fecha'];
    public $timestamps = false;

    public function computadora(): BelongsTo
    {
        return $this->belongsTo(Computadora::class, 'idComputadora');
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idEmpleado');
    }

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'idVenta');
    }
}
