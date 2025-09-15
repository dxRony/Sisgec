<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Factura;

class FacturaController extends Controller
{
    public function ver($id)
    {
        //obteniendo la factura de la Db
        $factura = Factura::with('venta.detalles.computadora.componentes', 'venta.detalles.componente', 'venta.empleado')
            ->findOrFail($id);
        //mandando a vista con la factua
        return view('admin.facturas.ver', compact('factura'));
    }
}
