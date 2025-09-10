<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Computadora;
class ComputadoraController extends Controller
{
    public function listar()
    {
        $computadoras = Computadora::with('componentes')->where('personalizada', false)->get();
        return view('cliente.computadoras.listar', compact('computadoras'));
    }
}
