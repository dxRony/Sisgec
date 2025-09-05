<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleEnsamblaje extends Model
{
    protected $table = 'DetalleEnsamblaje';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null; // clave compuesta
    protected $fillable = ['idEnsamblaje', 'idComponente', 'cantidad'];

    // Relación con Ensamblaje
    public function ensamblaje(): BelongsTo
    {
        return $this->belongsTo(Ensamblaje::class, 'idEnsamblaje');
    }

    // Relación con Componente
    public function componente(): BelongsTo
    {
        return $this->belongsTo(Componente::class, 'idComponente');
    }
}
