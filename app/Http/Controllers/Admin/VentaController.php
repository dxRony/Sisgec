<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venta;

class VentaController extends Controller
{
    public function listar(Request $request)
    {
        //obteniendo las ventas
        $query = Venta::with([
            'factura',
            'detalles.computadora.componentes',
            'detalles.componente',
            'usuario',
            'empleado'
        ]);
        //validando filtros llenos
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
        }
        //filtro de cliente
        if ($request->filled('cliente')) {
            $query->whereHas('usuario', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->cliente . '%')->orWhere('id', $request->cliente);
            });
        }
        //filtro de tipo de componente
        if ($request->filled('producto')) {
            $query->whereHas('detalles', function ($q) use ($request) {
                $q->whereHas('computadora', function ($q2) use ($request) {
                    $q2->where('id', $request->producto);
                })->orWhereHas('componente', function ($q2) use ($request) {
                    $q2->where('marca', 'like', '%' . $request->producto . '%')->orWhere('tipoComponente', 'like', '%' . $request->producto . '%');
                });
            });
        }
        //de la query resultante se ordena por fecha
        $compras = $query->orderBy('fecha', 'desc')->get();
        //mandando a vista con las ventas 
        return view('admin.ventas.listar', compact('compras'));
    }
}
