<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Computadora;

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
        //validadno stock
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

        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad']++;
        } else {
            $carrito[$id] = [
                'id' => $computadora->id,
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
}
