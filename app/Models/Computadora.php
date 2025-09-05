<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Computadora extends Model
{
    protected $table = 'Computadora';
    protected $fillable = ['disponibilidad'];

    // Relación con Ensamblaje
    public function ensamblajes(): HasMany
    {
        return $this->hasMany(Ensamblaje::class, 'idComputadora');
    }

    // Relación con Componente (N-N)
    public function componentes(): BelongsToMany
    {
        return $this->belongsToMany(Componente::class, 'ComponenteComputadora', 'idComputadora', 'idComponente')
            ->withPivot('cantidad');
    }

    // Relación con DetalleVenta
    public function detallesVenta(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'idComputadora');
    }
}
