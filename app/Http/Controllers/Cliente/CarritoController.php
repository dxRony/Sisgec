<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Computadora;
use App\Models\Componente;

class CarritoController extends Controller
{

    public function index()
    {
        $carrito = session()->get('carrito', []);
        return view('cliente.carrito', compact('carrito'));
    }

    public function agregar($id)
    {
        $computadora = Computadora::with('componentes')->findOrFail($id);

        // validar stock
        if ($computadora->disponibilidad < 1) {
            return redirect()->back()->withErrors(['stock' => 'No hay disponibilidad de esta computadora.']);
        }

        $precioTotal = 0;
        foreach ($computadora->componentes as $c) {
            $cantidad = ($c->tipoComponente === 'Memoria RAM' || $c->tipoComponente === 'Almacenamiento')
                ? $c->pivot->cantidad
                : 1;
            $precioTotal += $c->precio * $cantidad;
        }

        $carrito = session()->get('carrito', []);
        $key = "computadora_" . $id; // evita colisión con componentes

        if (isset($carrito[$key])) {
            $carrito[$key]['cantidad']++;
        } else {
            $carrito[$key] = [
                'id' => $computadora->id,
                'tipo' => 'computadora',
                'nombre' => $computadora->nombre,
                'precio' => $precioTotal,
                'cantidad' => 1
            ];
        }

        session()->put('carrito', $carrito);

        return redirect()->back()->with('success', 'Computadora agregada al carrito.');
    }

    public function eliminar($id)
    {
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session()->put('carrito', $carrito);
        }
        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito.');
    }


    public function agregarComponente(Request $request, $id)
    {
        $componente = Componente::findOrFail($id);
        $cantidad = $request->input('cantidad', 1);

        if ($cantidad < 1) {
            return back()->withErrors(['cantidad' => 'La cantidad debe ser al menos 1.']);
        }

        if ($componente->stock < $cantidad) {
            return back()->withErrors(['stock' => 'No hay suficiente stock disponible de este componente.']);
        }

        $carrito = session()->get('carrito', []);

        $key = "componente_" . $id; // evita colisión con computadoras

        if (isset($carrito[$key])) {
            $carrito[$key]['cantidad'] += $cantidad;
        } else {
            $carrito[$key] = [
                'id' => $componente->id,
                'tipo' => 'componente',
                'nombre' => $componente->tipoComponente . ' ' . $componente->marca,
                'precio' => $componente->precio,
                'cantidad' => $cantidad
            ];
        }

        session()->put('carrito', $carrito);

        return redirect()->back()->with('success', 'Componente agregado al carrito.');
    }
}
