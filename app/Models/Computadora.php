<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Computadora extends Model
{
    protected $table = 'Computadora';
    public $timestamps = false;
    protected $fillable = [
        'disponibilidad',
        'personalizada'
    ];

    public function ensamblajes(): HasMany
    {
        return $this->hasMany(Ensamblaje::class, 'idComputadora');
    }

    public function componentes(): BelongsToMany
    {
        return $this->belongsToMany(Componente::class, 'ComponenteComputadora', 'idComputadora', 'idComponente')
            ->withPivot('cantidad');
    }

    public function detallesVenta(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'idComputadora');
    }
}
