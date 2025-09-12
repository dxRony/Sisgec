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
        //mostrando en consola que entre al metodo
        logger()->info('Entrando a realizarCompra');
        $carrito = session()->get('carrito', []);
        //mostrando en consola el contenido del carrito
        logger()->info('Contenido del carrito: ', $carrito);

        //guardando usuario autenticado
        $user = Auth::user();
        logger()->info('Usuario autenticado: ', ['id' => $user->id, 'name' => $user->name]);

        if (empty($carrito)) {
            return back()->withErrors(['carrito' => 'El carrito está vacío.']);
        }

        DB::beginTransaction();
        try {
            logger()->info('Iniciando transacción de compra');
            //creando venta
            $venta = Venta::create([
                'total' => $this->calcularTotalCompra($carrito),
                'nitUsuario' => $user->id, // cliente actual
                'nitEmpleado' => 1, // ejemplo: asignar empleado fijo o desde auth
                'estado' => 'en proceso',
            ]);
            logger()->info('Venta creada: ', ['id' => $venta->id, 'total' => $venta->total]);   
            $todoEnsamblado = true;

            //recorriendo cada item en el carrito (computadora y componente)
            foreach ($carrito as $item) {
                if (isset($item['idComputadora'])) {
                    //si hay idComputadora
                    $computadora = Computadora::findOrFail($item['idComputadora']);
                    //datos de computadora
                    logger()->info('Procesando computadora: ', ['id' => $computadora->id, 'nombre' => $computadora->nombre, 'personalizada' => $computadora->personalizada]);   
                    //creandp detalle venta
                    DetalleVenta::create([
                        'idVenta' => $venta->id,
                        'idComputadora' => $computadora->id,
                        'cantidad' => $item['cantidad'],
                        'subtotal' => $computadora->precio * $item['cantidad'],
                    ]);

                    //definiendo ensamblaje 
                    $estadoEnsamblaje = $computadora->personalizada ? 'en proceso' : 'ensamblado';
                    Ensamblaje::create([
                        'idVenta' => $venta->id,
                        'idComputadora' => $computadora->id,
                        'idEmpleado' => 1, // empleado asignado
                        'estado' => $estadoEnsamblaje,
                        'fecha' => now(),
                    ]);

                    if ($estadoEnsamblaje !== 'ensamblado') {
                        $todoEnsamblado = false;
                    }
                } elseif (isset($item['idComponente'])) {
                    //si hay componente
                    $componente = Componente::findOrFail($item['idComponente']);

                    //creando detalle venta
                    DetalleVenta::create([
                        'idVenta' => $venta->id,
                        'idComponente' => $componente->id,
                        'cantidad' => $item['cantidad'],
                        'subtotal' => $componente->precio * $item['cantidad'],
                    ]);
                }
            }

            // creando factura si no hay ensamblajes pendientes
            if ($todoEnsamblado) {
                Ensamblaje::where('idVenta', $venta->id)->update(['estado' => 'vendido']);

                $venta->update(['estado' => 'finalizada']);

                Factura::create([
                    'idVenta' => $venta->id,
                    'nit' => $venta->nitUsuario,
                    'fecha' => now(),
                    'numeroFactura' => 'FACTURA NO. ' . str_pad($venta->id, 6, '0', STR_PAD_LEFT),
                ]);
            }

            DB::commit();

            //limpiando carrito
            session()->forget('carrito');

            return redirect()->route('carrito.index')->with('success', 'Compra realizada con éxito.');
        } catch (\Exception $e) {
            logger()->error('Error en la compra: ' . $e->getMessage());
            //linea del error
            logger()->error('Línea del error: ' . $e->getLine());
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al procesar la compra: ' . $e->getMessage()]);
        }
    }

    private function calcularTotalCompra($carrito)
    {
        logger()->info('Calculando total de la compra');
        $total = 0;
        foreach ($carrito as $item) {
            if (isset($item['idComputadora'])) {
                $computadora = Computadora::find($item['idComputadora']);
                $total += $computadora->precio * $item['cantidad'];
            } elseif (isset($item['idComponente'])) {
                $componente = Componente::find($item['idComponente']);
                $total += $componente->precio * $item['cantidad'];
            }
        }
        logger()->info('Total calculado: ' . $total);
        return $total;
    }
}
