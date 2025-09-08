<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Componente extends Model
{
    public $timestamps = false;
    protected $table = 'Componente';
    protected $fillable = [
        'tipoComponente',
        'marca',
        'consumoEnergetico',
        'nucleos',
        'velocidad',
        'capacidad',
        'tipo',
        'potencia',
        'eficiencia',
        'color',
        'stock',
        'precio'
    ];

    public function computadoras(): BelongsToMany
    {
        return $this->belongsToMany(Computadora::class, 'ComponenteComputadora', 'idComponente', 'idComputadora')
            ->withPivot('cantidad');
    }

    public function detallesEnsamblaje(): HasMany
    {
        return $this->hasMany(DetalleEnsamblaje::class, 'idComponente');
    }

    public function detallesVenta(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'idComponente');
    }
}
