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
        //obteniendo las computadoras ya armadass
        $computadoras = Computadora::with('componentes')->where('personalizada', false)->get();
        //enviando a vista con las computadoras
        return view('cliente.computadoras.listar', compact('computadoras'));
    }

    public function personalizarV($id)
    {
        //obteniendo la computadora con sus componentes dado el id
        $computadora = Computadora::with('componentes')->findOrFail($id);
        //agrupando los componentes por el atributo tipoComponente
        $agrupados = Componente::all()->groupBy('tipoComponente');
        //componentes ya seleccionados en la computadora actualmente
        $seleccionados = $computadora->componentes->keyBy('tipoComponente');
        //enviando a vista con los instrumentos necesarios
        return view('cliente.computadoras.personalizar', compact('computadora', 'agrupados', 'seleccionados'));
    }

    public function personalizar(Request $request, $id)
    {
        //obteniendo computadora con sus componentes
        $original = Computadora::with('componentes')->findOrFail($id);
        //obteniendo los componentes seleccionados de la vista
        $datos = $request->input('componentes', []);
        //validando componente a componente
        foreach ($datos as $tipo => $info) {
            //obteniendo de del componente y la cantidad
            $componenteId = $info['id'];
            $cantidad = $info['cantidad'] ?? 1;
            //obteniendo componente de la DB
            $componente = Componente::findOrFail($componenteId);
            //validando stock del componente
            if ($componente->stock < $cantidad) {
                return back()->withErrors([
                    "componentes.$tipo" => "Stock insuficiente para {$componente->tipoComponente} {$componente->marca}."
                ]);
            }
        }
        //creando nueva computadora a partir de la original
        $nuevaComputadora = $original->replicate();
        //definiendo una unica existencia
        $nuevaComputadora->disponibilidad = 1;
        //definiendola como personalizada
        $nuevaComputadora->personalizada = true;
        //guardando en la DB
        $nuevaComputadora->save();
        //asocianado los componentes seleccionados a la computadora nueva
        foreach ($datos as $tipo => $info) {
            $componenteId = $info['id'];
            $cantidad = $info['cantidad'] ?? 1;
            $nuevaComputadora->componentes()->attach($componenteId, ['cantidad' => $cantidad]);
        }
        //agregando la computadora al carrito por el id
        app(CarritoController::class)->agregar($nuevaComputadora->id);
        //enviando la vista con el msj
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
        //enviando a vista con los componentes separados por tipo
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
        //validando datos del form en la DB
        $request->validate([
            'componentes.procesador' => 'nullable|integer|exists:Componente,id',
            'componentes.fuente' => 'nullable|integer|exists:Componente,id',
            'componentes.gabinete' => 'nullable|integer|exists:Componente,id',
        ]);
        //validando que hayan seleccionado los componentes
        if (empty($request->componentes['procesador'])) {
            return back()->withErrors(['procesador' => 'Debe seleccionar un procesador.']);
        }
        if (empty($request->componentes['fuente'])) {
            return back()->withErrors(['fuente' => 'Debe seleccionar una fuente de poder.']);
        }
        if (empty($request->componentes['gabinete'])) {
            return back()->withErrors(['gabinete' => 'Debe seleccionar un gabinete.']);
        }
        $ids = array_filter([
            $request->componentes['procesador'] ?? null,
            $request->componentes['fuente'] ?? null,
            $request->componentes['gabinete'] ?? null,
        ]);
        //validando ram y almacenamiebntos
        if (!empty($request->componentes['rams'])) {
            foreach ($request->componentes['rams'] as $idRam => $val) {
                $ids[] = $idRam;
            }
        }
        if (!empty($request->componentes['storages'])) {
            foreach ($request->componentes['storages'] as $idStorage => $val) {
                $ids[] = $idStorage;
            }
        }
        //obteniendo todos los componentes seleccionados
        $componentes = Componente::whereIn('id', $ids)->get()->keyBy('id');
        //validando stock de cada componente seleccionado
        foreach (['procesador', 'fuente', 'gabinete'] as $tipo) {
            $comp = $componentes[$request->componentes[$tipo]];
            if ($comp->stock < 1) {
                return back()->withErrors([$tipo => "No hay stock suficiente del {$comp->tipoComponente} {$comp->marca}."]);
            }
        }
        //validando stock de rams y almacenamientos
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
        //creando la computadora
        $computadora = Computadora::create([
            'disponibilidad' => 1,
            'personalizada' => true,
        ]);
        // relacionando los componentes con la computadora
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
        //agregando la computadora al carrito por el id
        app(CarritoController::class)->agregar($computadora->id);
        //enviando a vista con msj
        return redirect()->route('cliente.index')->with('success', 'Computadora personalizada agregada al carrito.');
    }
}
