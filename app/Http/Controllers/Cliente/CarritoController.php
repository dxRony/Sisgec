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
        //obteniendo el carrito de la sesion
        $carrito = session()->get('carrito', []);
        //enviando a vista con el carrito 
        return view('cliente.carrito', compact('carrito'));
    }

    public function agregar($id)
    {
        //obteniendo computadora por el id
        $computadora = Computadora::with('componentes')->findOrFail($id);
        // validar que haya disponibilidad
        if ($computadora->disponibilidad <= 0) {
            return redirect()->back()->with('error', 'No hay disponibilidad de esta computadora.');
        }
        //definiendo precio de la computadora
        $precioTotal = 0;
        //recorriendo todos los componentes para sumar al precio
        foreach ($computadora->componentes as $componente) {
            $cantidad = ($componente->tipoComponente === 'Memoria RAM' || $componente->tipoComponente === 'Almacenamiento')
                ? $componente->pivot->cantidad
                : 1;
            $precioTotal += $componente->precio * $cantidad;
        }
        //obteniendo carruto de la sesion
        $carrito = session()->get('carrito', []);
        //definiendo clave de la computadora en el carrito
        $key = "computadora_" . $id;
        //si la computadora ya existe en el carrito, se suma la cantidad
        if (isset($carrito[$key])) {
            $carrito[$key]['cantidad']++;
        } else { //si no se crea la entidad en el carrito
            $carrito[$key] = [
                'id' => $computadora->id,
                'tipo' => 'computadora',
                'nombre' => $computadora->nombre,
                'precio' => $precioTotal,
                'cantidad' => 1
            ];
        }
        //guardando el carrito de la sesion
        session()->put('carrito', $carrito);
        //enviandoa vista con msj
        return redirect()->back()->with('success', 'Computadora agregada al carrito.');
    }

    public function eliminar($id)
    {
        //obteniendo carruito de la sesion
        $carrito = session()->get('carrito', []);
        //si existe el elemento con el id indicado, se elimina
        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session()->put('carrito', $carrito);
        }
        //enciando a vista con msj
        return redirect()->route('cliente.carrito.index')->with('success', 'Producto eliminado del carrito.');
    }

    public function agregarComponente(Request $request, $id)
    {
        //obteniendo componente por el id
        $componente = Componente::findOrFail($id);
        //obteniendo la cantidad, si no se especifica, es 1
        $cantidad = $request->input('cantidad', 1);
        //validando que no sea una cantidad negativa
        if ($cantidad <= 0) {
            return redirect()->back()->with('error', 'No hay stock de este componente.');
        }
        //validando que haya stock del componente solicitado
        if ($componente->stock < $cantidad) {
            return redirect()->back()->with('error', 'No hay suficiente stock disponible de este componente.');
        }
        //obteniedo carrito de la sesion
        $carrito = session()->get('carrito', []);
        //definiendo clave del componente en el carrito
        $key = "componente_" . $id;
        //si ya existe en el carrito la entidad, se aumenta la cantidad
        if (isset($carrito[$key])) {
            $carrito[$key]['cantidad'] += $cantidad;
        } else {//si no se crea la entidad en el carrito
            $carrito[$key] = [
                'id' => $componente->id,
                'tipo' => 'componente',
                'nombre' => $componente->tipoComponente . ' ' . $componente->marca,
                'precio' => $componente->precio,
                'cantidad' => $cantidad
            ];
        }
        //guardando el carrito de la sesion
        session()->put('carrito', $carrito);
        //enviando a vista con el msj
        return redirect()->back()->with('success', 'Componente agregado al carrito.');
    }
}
