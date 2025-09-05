<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Componente extends Model
{
    protected $table = 'Componente';
    protected $fillable = [
        'tipoComponente', 'marca', 'consumoEnergetico',
        'nucleos', 'velocidad', 'capacidad', 'tipo',
        'potencia', 'eficiencia', 'color', 'stock', 'precio'
    ];

    // Relación con Computadora (N-N)
    public function computadoras(): BelongsToMany
    {
        return $this->belongsToMany(Computadora::class, 'ComponenteComputadora', 'idComponente', 'idComputadora')
                    ->withPivot('cantidad');
    }

    // Relación con DetalleEnsamblaje
    public function detallesEnsamblaje(): HasMany
    {
        return $this->hasMany(DetalleEnsamblaje::class, 'idComponente');
    }

    // Relación con DetalleVenta
    public function detallesVenta(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'idComponente');
    }
}
