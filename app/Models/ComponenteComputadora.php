<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComponenteComputadora extends Model
{
    protected $table = 'ComponenteComputadora';
    public $timestamps = false;

    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'idComputadora',
        'idComponente',
        'cantidad'
    ];

    public function computadora(): BelongsTo
    {
        return $this->belongsTo(Computadora::class, 'idComputadora');
    }

    public function componente(): BelongsTo
    {
        return $this->belongsTo(Componente::class, 'idComponente');
    }
}
