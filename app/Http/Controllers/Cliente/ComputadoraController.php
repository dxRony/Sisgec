<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Computadora;
use Illuminate\Support\Facades\DB;
use App\Models\Componente;
use App\Http\Controllers\Cliente\CarritoController as CarritoController;

class ComputadoraController extends Controller
{
    public function listar()
    {
        $computadoras = Computadora::with('componentes')->where('personalizada', false)->get();
        return view('cliente.computadoras.listar', compact('computadoras'));
    }

    public function personalizarV($id)
    {
        $computadora = Computadora::with('componentes')->findOrFail($id);
        $agrupados = Componente::all()->groupBy('tipoComponente');
        $seleccionados = $computadora->componentes->keyBy('tipoComponente');
        return view('cliente.computadoras.personalizar', compact('computadora', 'agrupados', 'seleccionados'));
    }

    public function guardarPersonalizada(Request $request, $id)
    {
        $original = Computadora::with('componentes')->findOrFail($id);
        $datos = $request->input('componentes', []);

        $nuevaComputadora = $original->replicate();
        $nuevaComputadora->disponibilidad = 1;
        $nuevaComputadora->personalizada = true;
        $nuevaComputadora->save();

        foreach ($datos as $tipo => $componente) {
            $cantidad = $componente['cantidad'] ?? 1;
            $nuevaComputadora->componentes()->attach($componente['id'], ['cantidad' => $cantidad]);
        }

        app(CarritoController::class)->agregar($nuevaComputadora->id);
        return redirect()->route('cliente.index')->with('success', 'Computadora personalizada agregada al carrito.');
    }

    public function guardarCreadaDesdeCero() {
        
    }
}
