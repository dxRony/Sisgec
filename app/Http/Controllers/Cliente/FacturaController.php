<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Factura;

class FacturaController extends Controller
{
    public function ver($id)
    {
        $factura = Factura::with('venta.detalles.computadora.componentes', 'venta.detalles.componente', 'venta.empleado')
            ->findOrFail($id);

        return view('cliente.facturas.ver', compact('factura'));
    }
}
