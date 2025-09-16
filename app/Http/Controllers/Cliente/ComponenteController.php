<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Componente;

class ComponenteController extends Controller
{
    public function listar()
    {
        //obteniendo los componentes
        $componentes = Componente::all();
        //enviando a vista con los componentes
        return view('cliente.componentes.listar', compact('componentes'));
    }
}
