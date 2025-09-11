<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Computadora;
use App\Models\Componente;

class ComputadoraController extends Controller
{
    public function listar()
    {
        $computadoras = Computadora::with('componentes')->where('personalizada', false)->get();
        return view('admin.computadoras.listar', compact('computadoras'));
    }

    public function registrarV()
    {
        //obteniendo todos los componentes por tipo de la DB
        $procesadores = Componente::where('tipoComponente', 'Procesador')->get();
        $fuentes      = Componente::where('tipoComponente', 'Fuente De Poder')->get();
        $gabinetes    = Componente::where('tipoComponente', 'Gabinete')->get();
        $rams         = Componente::where('tipoComponente', 'Memoria RAM')->get();
        $storages     = Componente::where('tipoComponente', 'Almacenamiento')->get();

        return view('admin.computadoras.registrar', compact(
            'procesadores',
            'fuentes',
            'gabinetes',
            'rams',
            'storages'
        ));
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'disponibilidad' => 'required|integer|min:1',
            'componentes.procesador' => 'nullable|integer|exists:Componente,id',
            'componentes.fuente' => 'nullable|integer|exists:Componente,id',
            'componentes.gabinete' => 'nullable|integer|exists:Componente,id',
        ]);

        if (empty($request->componentes['procesador'])) {
            return back()->withErrors(['procesador' => 'Debe seleccionar un procesador.']);
        }

        if (empty($request->componentes['fuente'])) {
            return back()->withErrors(['fuente' => 'Debe seleccionar una fuente de poder.']);
        }

        if (empty($request->componentes['gabinete'])) {
            return back()->withErrors(['gabinete' => 'Debe seleccionar un gabinete.']);
        }

        $disponibilidad = $request->disponibilidad;

        // Recolectamos todos los componentes a usar
        $componentes = [
            ['id' => $request->componentes['procesador'], 'cantidad' => 1],
            ['id' => $request->componentes['fuente'], 'cantidad' => 1],
            ['id' => $request->componentes['gabinete'], 'cantidad' => 1],
        ];

        if (!empty($request->componentes['rams'])) {
            foreach ($request->componentes['rams'] as $idRam => $val) {
                $cantidad = $request->cantidades['rams'][$idRam] ?? 1;
                $componentes[] = ['id' => $idRam, 'cantidad' => $cantidad];
            }
        }

        if (!empty($request->componentes['storages'])) {
            foreach ($request->componentes['storages'] as $idStorage => $val) {
                $cantidad = $request->cantidades['storages'][$idStorage] ?? 1;
                $componentes[] = ['id' => $idStorage, 'cantidad' => $cantidad];
            }
        }

        // validando stock de los componentes
        foreach ($componentes as $c) {
            $comp = Componente::findOrFail($c['id']);
            $cantidadNecesaria = $c['cantidad'] * $disponibilidad;

            if ($comp->stock < $cantidadNecesaria) {
                return back()->withErrors([
                    'stock' => "El componente {$comp->tipoComponente} ({$comp->marca}) no tiene suficiente stock. 
                            Stock actual: {$comp->stock}, requerido: {$cantidadNecesaria}."
                ]);
            }
        }

        //si hay stock suficiente, se crea la computadora
        $computadora = Computadora::create([
            'disponibilidad' => $disponibilidad,
            'personalizada' => false,
        ]);

        // relacionando componentes y descontando stock
        foreach ($componentes as $c) {
            $comp = Componente::findOrFail($c['id']);
            $computadora->componentes()->attach($comp->id, ['cantidad' => $c['cantidad']]);

            // Descontar stock
            $comp->stock -= $c['cantidad'] * $disponibilidad;
            $comp->save();
        }

        return redirect()->route('admin.computadoras.listar')
            ->with('success', 'Computadora registrada correctamente.');
    }

    public function editarV($id)
    {
        $computadora = Computadora::with('componentes')->findOrFail($id);
        $componentes = Componente::all();

        return view('admin.computadoras.editar', compact('computadora', 'componentes'));
    }

    public function editar(Request $request, $id)
    {
        $computadora = Computadora::findOrFail($id);

        $request->validate([
            'disponibilidad' => 'required|integer|min:1',
        ]);

        $computadora->update([
            'disponibilidad' => $request->disponibilidad,
        ]);

        return redirect()->route('admin.computadoras.listar')->with('success', 'Computadora actualizada correctamente.');
    }

    public function eliminar($id)
    {
        $computadora = Computadora::findOrFail($id);
        $computadora->delete();

        return redirect()->route('admin.computadoras.listar')->with('success', 'Computadora eliminada correctamente.');
    }
}
