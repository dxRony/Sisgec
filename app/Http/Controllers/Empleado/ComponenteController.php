<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Componente;


class ComponenteController extends Controller
{
    public function listar()
    {
        $componentes = Componente::all();
        return view('empleado.componentes.listar', compact('componentes'));
    }
}
