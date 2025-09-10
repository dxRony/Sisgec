<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;
use App\Models\Computadora;

use Illuminate\Http\Request;

class ComputadoraController extends Controller
{
    public function listar()
    {
        $computadoras = Computadora::with('componentes')->where('personalizada', false)->get();
        return view('empleado.computadoras.listar', compact('computadoras'));
    }
}
