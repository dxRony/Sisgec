<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;
use App\Models\Ensamblaje;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Factura;

class EnsamblajeController extends Controller
{
    public function listar()
    {
        //obteniendo los ensamblajes que estan en proceso y no tienen empleado asignado
        $ensamblajes = Ensamblaje::all()->where('estado', 'en proceso')->where('idEmpleado', null);
        //enviando a vista con los ensamblajes
        return view('empleado.ensamblajes.listar', compact('ensamblajes'));
    }

    public function adquirir($id)
    {
        //obteniendo el ensamblaje por id y el usuario de la sesion
        $ensamblaje = Ensamblaje::where('id', $id);
        $user = Auth::user();
        //agregando el id del empleado que adquirio el ensamblaje
        $ensamblaje->update(['idEmpleado' => $user->id]);
        //redireccionando a la lista de ensamblajes con msj
        return redirect()->back()->with('success', 'Ensamblaje adquirido.');
    }

    public function listarMios()
    {
        //obteniendo al usuario de la sesio
        $user = Auth::user();
        //obteniendo sus esnamblajes en proceso
        $ensamblajes = Ensamblaje::all()->where('estado', 'en proceso')->where('idEmpleado', $user->id);
        //enviando a vista con los ensamblajes
        return view('empleado.ensamblajes.listarMios', compact('ensamblajes'));
    }

    public function ensamblar($id)
    {
        //obteniendo el ensamblaje por id
        $ensamblaje = Ensamblaje::find($id);
        if (!$ensamblaje) {
            return redirect()->back()->with('error', 'Ensamblaje no encontrado.');
        }
        //cambiando el estado del ensamblaje a ensamblado
        $ensamblaje->update(['estado' => 'ensamblado']);
        //evaluando si todos los ensamblajes de la venta estan ensamblados
        $this->evaluarVenta($ensamblaje->idVenta);
        //redireccionando a la lista de mis ensamblajes con msj
        return redirect()->back()->with('success', 'Ensamblaje marcado como ensamblado.');
    }

    private function evaluarVenta($idVenta)
    {
        //obteniendo todos los ensamblajes de la venta
        $ensamblajes = Ensamblaje::where('idVenta', $idVenta)->get();

        foreach ($ensamblajes as $ensamblaje) {
            //si alguno no esta ensamblado, se aborta
            if ($ensamblaje->estado != 'ensamblado') {
                return;
            }
        }
        foreach ($ensamblajes as $ensamblaje) {
            //si todos estan ensamblados, se cambia el estado de cada ensamblaje a completado
            $ensamblaje->update(['estado' => 'vendido']);
        }
        //si todos estan ensamblados, se cambia el estado de la venta a completado
        $venta = Venta::find($idVenta);
        if ($venta) {
            $venta->update(['estado' => 'finalizada']);

            // creando la factura
            Factura::create([
                'idVenta' => $venta->id,
                'nit' => $venta->nitUsuario,
                'fecha' => now(),
                'numeroFactura' => 'FACTURA NO. ' . str_pad($venta->id, 6, '0', STR_PAD_LEFT),
            ]);
        }
    }
}
