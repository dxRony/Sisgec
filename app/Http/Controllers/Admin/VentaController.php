<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function listar(Request $request)
    {
        $query = \App\Models\Venta::with([
            'factura',
            'detalles.computadora.componentes',
            'detalles.componente',
            'usuario',
            'empleado'
        ]);

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
        }

        if ($request->filled('cliente')) {
            $query->whereHas('usuario', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->cliente . '%')
                    ->orWhere('id', $request->cliente); // buscar por nombre o id
            });
        }

        if ($request->filled('producto')) {
            $query->whereHas('detalles', function ($q) use ($request) {
                $q->whereHas('computadora', function ($q2) use ($request) {
                    $q2->where('id', $request->producto);
                })
                    ->orWhereHas('componente', function ($q2) use ($request) {
                        $q2->where('marca', 'like', '%' . $request->producto . '%')
                            ->orWhere('tipoComponente', 'like', '%' . $request->producto . '%');
                    });
            });
        }

        $compras = $query->orderBy('fecha', 'desc')->get();

        return view('admin.ventas.listar', compact('compras'));
    }
}
