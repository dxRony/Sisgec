<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComponenteComputadora extends Model
{
    protected $table = 'ComponenteComputadora';
    public $timestamps = false;

    // La clave primaria es compuesta (idComputadora, idComponente), así que desactivamos incrementing
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'idComputadora',
        'idComponente',
        'cantidad'
    ];

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
