<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Computadora;
use App\Models\Componente;
use App\Models\User;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito = session()->get('carrito', []);
        //obteniendo usuarios con rol = 3, que son clientes
        $clientes = User::where('rol', 3)->get();
        return view('empleado.carrito', compact('carrito', 'clientes'));
    }

    public function agregar($id)
    {
        //obteniendo id de la computadora seleccionada con sus componentes
        $computadora = Computadora::with('componentes')->findOrFail($id);
        //validando que haya disponibilidada de la computadora seleccionada
        if ($computadora->disponibilidad <= 0) {
            return redirect()->back()->with('error', 'No hay disponibilidad de esta computadora.');
        }
        //definiendo el precio de la computadora
        $precioTotal = 0;
        foreach ($computadora->componentes as $componente) {
            $cantidad = ($componente->tipoComponente === 'Memoria RAM' || $componente->tipoComponente === 'Almacenamiento')
                ? $componente->pivot->cantidad
                : 1;
            $precioTotal += $componente->precio * $cantidad;
        }
        //obteniendo el carrito de la sesion
        $carrito = session()->get('carrito', []);
        //creando clave de la computadora
        $key = "computadora_" . $id;
        //si la computadora ya existe en el carrito, se incrementa la cantidad
        if (isset($carrito[$key])) {
            $carrito[$key]['cantidad']++;
        } else {//si no se crea la "entidad" en el carrito
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
        //regresando con el msj
        return redirect()->back()->with('success', 'Computadora agregada al carrito.');
    }

    public function eliminar($id)
    {
        //obteniendo carrito de la sesion
        $carrito = session()->get('carrito', []);
        //si el objeto esta en el carrito, se elimina
        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session()->put('carrito', $carrito);
        }
        //enviando a vista con msj
        return redirect()->route('empleado.carrito.index')->with('success', 'Producto eliminado del carrito.');
    }


    public function agregarComponente(Request $request, $id)
    {
        //obteniendo componente con el id seleccionado
        $componente = Componente::findOrFail($id);
        //obteniendo la cantidad del componente, si no especifica es 1
        $cantidad = $request->input('cantidad', 1);
        //validando acantidad
        if ($cantidad <= 0) {
            return redirect()->back()->with('error', 'Cantidada incorrecta.');
        }
        //validando stock del componente
        if ($componente->stock < $cantidad) {
            return back()->with('error', 'No hay suficiente stock disponible de este componente.');
        }
        //obteniendo carrito de la sesion
        $carrito = session()->get('carrito', []);
        //creando clave del componente 
        $key = "componente_" . $id;
        //si el componente ya se encuentra en el carrito, se suma la cantidad
        if (isset($carrito[$key])) {
            $carrito[$key]['cantidad'] += $cantidad;
        } else { //si no se crea la "entidad" en el carrito
            $carrito[$key] = [
                'id' => $componente->id,
                'tipo' => 'componente',
                'nombre' => $componente->tipoComponente . ' ' . $componente->marca,
                'precio' => $componente->precio,
                'cantidad' => $cantidad
            ];
        }
        //guardando carrito de la sesion
        session()->put('carrito', $carrito);
        //regresando a vista con msj
        return redirect()->back()->with('success', 'Componente agregado al carrito.');
    }
}
