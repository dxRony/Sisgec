<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetalleVenta;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function componentesMasVendidos()
    {
        // Agrupar por componente y contar total vendido
        $componentes = DetalleVenta::select(
            'idComponente',
            DB::raw('SUM(cantidad) as total_vendido')
        )
            ->whereNotNull('idComponente') // solo componentes, no computadoras
            ->groupBy('idComponente')
            ->with('componente') // traer datos del componente
            ->orderByDesc('total_vendido')
            ->get();

        return view('admin.reportes.componentes', compact('componentes'));
    }
}
