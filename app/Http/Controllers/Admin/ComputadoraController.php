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
        //validando componentes
        if (empty($request->componentes['procesador'])) {
            return back()->withErrors(['procesador' => 'Debe seleccionar un procesador.']);
        }

        if (empty($request->componentes['fuente'])) {
            return back()->withErrors(['fuente' => 'Debe seleccionar una fuente de poder.']);
        }

        if (empty($request->componentes['gabinete'])) {
            return back()->withErrors(['gabinete' => 'Debe seleccionar un gabinete.']);
        }

        $computadora = Computadora::create([
            'disponibilidad' => $request->disponibilidad,
            'personalizada' => false,
        ]);

        // relacionando componentes
        $computadora->componentes()->attach($request->componentes['procesador'], ['cantidad' => 1]);
        $computadora->componentes()->attach($request->componentes['fuente'], ['cantidad' => 1]);
        $computadora->componentes()->attach($request->componentes['gabinete'], ['cantidad' => 1]);

        // relacionando memorias RAM
        if (!empty($request->componentes['rams'])) {
            foreach ($request->componentes['rams'] as $idRam => $val) {
                $cantidad = $request->cantidades['rams'][$idRam] ?? 1;
                $computadora->componentes()->attach($idRam, ['cantidad' => $cantidad]);
            }
        }

        // relacionando almacenamientos
        if (!empty($request->componentes['storages'])) {
            foreach ($request->componentes['storages'] as $idStorage => $val) {
                $cantidad = $request->cantidades['storages'][$idStorage] ?? 1;
                $computadora->componentes()->attach($idStorage, ['cantidad' => $cantidad]);
            }
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
