<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Componente;

class ComponenteController extends Controller
{
    public function listar()
    {
        $componentes = Componente::all();
        return view('cliente.componentes.listar', compact('componentes'));
    }
}
