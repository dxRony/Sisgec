<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Ensamblaje;
use App\Models\Factura;
use App\Models\Computadora;
use App\Models\Componente;

class ComprarController extends Controller
{
    public function realizarCompra(Request $request)
    {
        //obteniendo carrito de la sesion
        $carrito = session()->get('carrito', []);
        //obteniendo usuario de la sesion
        $user = Auth::user();
        if (empty($carrito)) {
            return back()->withErrors(['carrito' => 'El carrito está vacío.']);
        }

        //transaccion-rollback, por si algo sale mal
        DB::beginTransaction();
        try {
            //creando venta
            $venta = Venta::create([
                'total' => $this->calcularTotalCompra($carrito),
                'nitUsuario' => $user->id,
                'nitEmpleado' => 202031191,
                'estado' => 'en proceso',
                'fecha' => now(),
            ]);
            //estado para determinar en la venta
            $todoEnsamblado = true;

            foreach ($carrito as $item) {
                //recorriendo cada item en el carrito
                if ($item['tipo'] === 'computadora') {
                    //si es de tipo computadora se obtiene por id
                    $computadora = Computadora::findOrFail($item['id']);
                    //si existe se crea el detalle venta
                    DetalleVenta::create([
                        'idVenta' => $venta->id,
                        'idComputadora' => $computadora->id,
                        'cantidad' => $item['cantidad'],
                        'subtotal' => $item['precio'] * $item['cantidad'],
                    ]);
                    //disminuyendo la disponibilidad de la computadora
                    $computadora->decrement('disponibilidad', $item['cantidad']);
                    //si la pc es personalizada se crea el ensamblaje
                    $estadoEnsamblaje = $computadora->personalizada ? 'en proceso' : 'ensamblado';
                    Ensamblaje::create([
                        'idVenta' => $venta->id,
                        'idComputadora' => $computadora->id,
                        'idEmpleado' => null, //OJOOOOOOOOOOOOOOO
                        'estado' => $estadoEnsamblaje,
                    ]);

                    if ($estadoEnsamblaje !== 'ensamblado') {
                        $todoEnsamblado = false;
                    }
                } elseif ($item['tipo'] === 'componente') {
                    $componente = Componente::findOrFail($item['id']);
                    //si el item en el carrito es componente se crea el detalleventa, no necesita todo el ensamblaje
                    DetalleVenta::create([
                        'idVenta' => $venta->id,
                        'idComponente' => $componente->id,
                        'cantidad' => $item['cantidad'],
                        'subtotal' => $item['precio'] * $item['cantidad'],
                    ]);
                    //disminuyendo la disponibilidad del componente
                    $componente->decrement('stock', $item['cantidad']);
                }
            }
            if ($todoEnsamblado) {
                //si todos los detalleVenta estan ensamblados, se crea la factura y se finaliza la compra
                Ensamblaje::where('idVenta', $venta->id)->update(['estado' => 'vendido']);
                $venta->update(['estado' => 'finalizada']);

                Factura::create([
                    'idVenta' => $venta->id,
                    'nit' => $venta->nitUsuario,
                    'fecha' => now(),
                    'numeroFactura' => 'FACTURA NO. ' . str_pad($venta->id, 6, '0', STR_PAD_LEFT),
                ]);
            }
            //confirmando inserts y vaciando el carrito
            DB::commit();
            session()->forget('carrito');
            return redirect()->route('cliente.carrito.index')->with('success', 'Compra realizada con éxito, consulta el estado de tu compra en "Mis compras".');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al procesar la compra: ' . $e->getMessage()]);
        }
    }

    private function calcularTotalCompra($carrito)
    {
        $total = 0;
        //recorriendo cada item en el carrito para determinar su total
        foreach ($carrito as $key => $item) {
            //obteniendo tipo del item
            $tipo = $item['tipo'] ?? null;
            //obteniendo el id del item
            $id = $item['id'];
            //obteniendo la cantidad, si no hay es 1
            $cantidad = isset($item['cantidad']) ? (int)$item['cantidad'] : 1;

            if (!$tipo || !$id) {
                logger()->warning('Item inválido en carrito (falta tipo o id)', ['key' => $key, 'item' => $item]);
                continue;
            }
            //obteniendo el precio
            $precioUnitario = isset($item['precio']) ? (float)$item['precio'] : null;
            if ($precioUnitario === null) {
                if ($tipo === 'computadora') {
                    $modelo = Computadora::find($id);
                    if (! $modelo) {
                        continue;
                    }
                    $precioUnitario = (float) $modelo->precio;
                } elseif ($tipo === 'componente') {
                    $modelo = Componente::find($id);
                    if (! $modelo) {
                        continue;
                    }
                    $precioUnitario = (float) $modelo->precio;
                }
            }
            $subtotal = $precioUnitario * $cantidad;
            $total += $subtotal;
        }
        return $total;
    }

    public function listarCompras()
    {
        //obteniendo usuario de la sesion
        $user = Auth::user();
        //obteniendo sus compras
        $compras = Venta::where('nitUsuario', $user->id)
            ->with(['factura', 'detalles.computadora.componentes', 'detalles.componente'])
            ->orderBy('fecha', 'desc')
            ->get();
        //enviando  vista con sus compras
        return view('cliente.compras.listar', compact('compras'));
    }
}
