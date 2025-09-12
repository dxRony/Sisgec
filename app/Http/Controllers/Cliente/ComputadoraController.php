<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Computadora;
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

    public function personalizar(Request $request, $id)
    {
        $original = Computadora::with('componentes')->findOrFail($id);
        $datos = $request->input('componentes', []);

        // Validar stock
        foreach ($datos as $tipo => $info) {
            $componenteId = $info['id'];   // ahora sí, tomamos el id correcto
            $cantidad = $info['cantidad'] ?? 1;

            $componente = Componente::findOrFail($componenteId);

            if ($componente->stock < $cantidad) {
                return back()->withErrors([
                    "componentes.$tipo" => "Stock insuficiente para {$componente->tipoComponente} {$componente->marca}."
                ]);
            }
        }

        // Clonar la computadora base
        $nuevaComputadora = $original->replicate();
        $nuevaComputadora->disponibilidad = 1;
        $nuevaComputadora->personalizada = true;
        $nuevaComputadora->save();

        // Asociar los componentes seleccionados
        foreach ($datos as $tipo => $info) {
            $componenteId = $info['id'];   // usar SIEMPRE el id real
            $cantidad = $info['cantidad'] ?? 1;

            $nuevaComputadora->componentes()->attach($componenteId, ['cantidad' => $cantidad]);
        }

        // Agregar al carrito
        app(CarritoController::class)->agregar($nuevaComputadora->id);

        return redirect()->route('cliente.index')->with('success', 'Computadora personalizada agregada al carrito.');
    }


    public function registrarV()
    {
        //obteniendo todos los componentes por tipo de la DB
        $procesadores = Componente::where('tipoComponente', 'Procesador')->get();
        $fuentes      = Componente::where('tipoComponente', 'Fuente De Poder')->get();
        $gabinetes    = Componente::where('tipoComponente', 'Gabinete')->get();
        $rams         = Componente::where('tipoComponente', 'Memoria RAM')->get();
        $storages     = Componente::where('tipoComponente', 'Almacenamiento')->get();

        return view('cliente.computadoras.registrar', compact(
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

        // validando stock de cada componente
        $ids = array_filter([
            $request->componentes['procesador'] ?? null,
            $request->componentes['fuente'] ?? null,
            $request->componentes['gabinete'] ?? null,
        ]);

        //ram
        if (!empty($request->componentes['rams'])) {
            foreach ($request->componentes['rams'] as $idRam => $val) {
                $ids[] = $idRam;
            }
        }
        //almacenamientos
        if (!empty($request->componentes['storages'])) {
            foreach ($request->componentes['storages'] as $idStorage => $val) {
                $ids[] = $idStorage;
            }
        }
        $componentes = Componente::whereIn('id', $ids)->get()->keyBy('id');

        foreach (['procesador', 'fuente', 'gabinete'] as $tipo) {
            $comp = $componentes[$request->componentes[$tipo]];
            if ($comp->stock < 1) {
                return back()->withErrors([$tipo => "No hay stock suficiente del {$comp->tipoComponente} {$comp->marca}."]);
            }
        }

        if (!empty($request->componentes['rams'])) {
            foreach ($request->componentes['rams'] as $idRam => $val) {
                $cantidad = $request->cantidades['rams'][$idRam] ?? 1;
                if ($componentes[$idRam]->stock < $cantidad) {
                    return back()->withErrors(["rams.$idRam" => "Stock insuficiente de RAM {$componentes[$idRam]->marca}."]);
                }
            }
        }

        if (!empty($request->componentes['storages'])) {
            foreach ($request->componentes['storages'] as $idStorage => $val) {
                $cantidad = $request->cantidades['storages'][$idStorage] ?? 1;
                if ($componentes[$idStorage]->stock < $cantidad) {
                    return back()->withErrors(["storages.$idStorage" => "Stock insuficiente de almacenamiento {$componentes[$idStorage]->marca}."]);
                }
            }
        }

        $computadora = Computadora::create([
            'disponibilidad' => 1,
            'personalizada' => true,
        ]);

        $computadora->componentes()->attach($request->componentes['procesador'], ['cantidad' => 1]);
        $computadora->componentes()->attach($request->componentes['fuente'], ['cantidad' => 1]);
        $computadora->componentes()->attach($request->componentes['gabinete'], ['cantidad' => 1]);

        if (!empty($request->componentes['rams'])) {
            foreach ($request->componentes['rams'] as $idRam => $val) {
                $cantidad = $request->cantidades['rams'][$idRam] ?? 1;
                $computadora->componentes()->attach($idRam, ['cantidad' => $cantidad]);
            }
        }

        if (!empty($request->componentes['storages'])) {
            foreach ($request->componentes['storages'] as $idStorage => $val) {
                $cantidad = $request->cantidades['storages'][$idStorage] ?? 1;
                $computadora->componentes()->attach($idStorage, ['cantidad' => $cantidad]);
            }
        }

        app(CarritoController::class)->agregar($computadora->id);
        return redirect()->route('cliente.index')->with('success', 'Computadora personalizada agregada al carrito.');
    }
}
