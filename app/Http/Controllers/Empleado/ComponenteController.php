<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Componente;


class ComponenteController extends Controller
{
    public function listar()
    {
        //obteniendo todos los componentes
        $componentes = Componente::all();
        //enviando a vista con todos los componentes
        return view('empleado.componentes.listar', compact('componentes'));
    }
}
