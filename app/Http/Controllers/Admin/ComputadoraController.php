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
        //obteniendo computadoras no personalizadas de la DB
        $computadoras = Computadora::with('componentes')->where('personalizada', false)->get();
        //mandando a vista con las computadoras obtenidas
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
        //enviando a vista con los componentes
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
        //validando datos del form
        $request->validate([
            'disponibilidad' => 'required|integer|min:1',
            'componentes.procesador' => 'nullable|integer|exists:Componente,id',
            'componentes.fuente' => 'nullable|integer|exists:Componente,id',
            'componentes.gabinete' => 'nullable|integer|exists:Componente,id',
            'componentes.rams' => 'nullable|array',
            'componentes.storages' => 'nullable|array',
        ]);
        //evaluando receta minima
        if (empty($request->componentes['procesador'])) {
            return back()->withErrors(['procesador' => 'Debe seleccionar un procesador.']);
        }
        if (empty($request->componentes['fuente'])) {
            return back()->withErrors(['fuente' => 'Debe seleccionar una fuente de poder.']);
        }
        if (empty($request->componentes['gabinete'])) {
            return back()->withErrors(['gabinete' => 'Debe seleccionar un gabinete.']);
        }
        if (empty($request->componentes['rams']) || count($request->componentes['rams']) < 1) {
            return back()->withErrors(['rams' => 'Debe seleccionar al menos una memoria RAM.']);
        }
        if (empty($request->componentes['storages']) || count($request->componentes['storages']) < 1) {
            return back()->withErrors(['storages' => 'Debe seleccionar al menos un dispositivo de almacenamiento.']);
        }
        $disponibilidad = $request->disponibilidad;
        //creando array de componentes y cantidades
        $componentes = [
            ['id' => $request->componentes['procesador'], 'cantidad' => 1],
            ['id' => $request->componentes['fuente'], 'cantidad' => 1],
            ['id' => $request->componentes['gabinete'], 'cantidad' => 1],
        ];
        //agregando rams y storages si existen
        foreach ($request->componentes['rams'] as $idRam => $val) {
            $cantidad = $request->cantidades['rams'][$idRam] ?? 1;
            $componentes[] = ['id' => $idRam, 'cantidad' => $cantidad];
        }
        foreach ($request->componentes['storages'] as $idStorage => $val) {
            $cantidad = $request->cantidades['storages'][$idStorage] ?? 1;
            $componentes[] = ['id' => $idStorage, 'cantidad' => $cantidad];
        }
        // validando stock de los componentes
        foreach ($componentes as $c) {
            //obteniendo componente
            $comp = Componente::findOrFail($c['id']);
            //determinando cantidad necesaria
            $cantidadNecesaria = $c['cantidad'] * $disponibilidad;
            //evaluando si se tiene la cantidad necesaria
            if ($comp->stock < $cantidadNecesaria) {
                return back()->withErrors([
                    'stock' => "El componente {$comp->tipoComponente} ({$comp->marca}) no tiene suficiente stock. 
                            Stock actual: {$comp->stock}, requerido: {$cantidadNecesaria}."
                ]);
            }
        }
        //creando la computadora en la db
        $computadora = Computadora::create([
            'disponibilidad' => $disponibilidad,
            'personalizada' => false,
        ]);
        // relacionando componentes y descontando stock
        foreach ($componentes as $c) {
            $comp = Componente::findOrFail($c['id']);
            $computadora->componentes()->attach($comp->id, ['cantidad' => $c['cantidad']]);
            // reduciendo stock de cada componente
            $comp->stock -= $c['cantidad'] * $disponibilidad;
            $comp->save();
        }
        //regresando vista con msj
        return redirect()->route('admin.computadoras.listar')->with('success', 'Computadora registrada correctamente.');
    }

    public function editarV($id)
    {
        //obteniendo la computadora con sus componentes
        $computadora = Computadora::with('componentes')->findOrFail($id);
        //mandando a vist acon la computadora a actualizar
        return view('admin.computadoras.editar', compact('computadora'));
    }

    public function editar(Request $request, $id)
    {
        //obteniendo la computadora
        $computadora = Computadora::findOrFail($id);
        //validando datos del fom
        $request->validate([
            'disponibilidad' => 'required|integer|min:1',
        ]);
        //actualizando computadora
        $computadora->update([
            'disponibilidad' => $request->disponibilidad,
        ]);
        //mandando a vista con msj
        return redirect()->route('admin.computadoras.listar')->with('success', 'Computadora actualizada correctamente.');
    }

    public function eliminar($id)
    {
        //obteniendo y eliminando la computadora
        $computadora = Computadora::findOrFail($id);
        $computadora->delete();
        //regresando con msj
        return redirect()->route('admin.computadoras.listar')->with('success', 'Computadora eliminada correctamente.');
    }
}
