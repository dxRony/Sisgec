<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetalleVenta;
use Illuminate\Support\Facades\DB;
use App\Models\ReporteCliente;
use App\Models\User;
use App\Models\Componente;
use App\Models\Computadora;

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

    public function computadorasMasVendidas()
    {
        // Agrupar por computadora y contar total vendido
        $computadoras = DetalleVenta::select(
            'idComputadora',
            DB::raw('SUM(cantidad) as total_vendido')
        )
            ->whereNotNull('idComputadora') // solo computadoras
            ->groupBy('idComputadora')
            ->with('computadora.componentes') // traer la computadora y sus componentes
            ->orderByDesc('total_vendido')
            ->get();

        return view('admin.reportes.computadoras', compact('computadoras'));
    }

    public function index(Request $request)
    {
        $query = ReporteCliente::query();

        // filtros dinámicos
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('idEmpleado')) {
            $query->where('idEmpleado', $request->idEmpleado);
        }
        if ($request->filled('idComponente')) {
            $query->where('idComponente', $request->idComponente);
        }
        if ($request->filled('idComputadora')) {
            $query->where('idComputadora', $request->idComputadora);
        }
        if ($request->filled('nitUsuario')) {
            $query->where('nitUsuario', $request->nitUsuario);
        }
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        $reportes = $query->orderBy('fecha', 'desc')->get();

        // para mostrar en selects
        $empleados = User::all()->where('rol', 2);
        $componentes = Componente::all();
        $computadoras = Computadora::all();
        $clientes = User::all()->where('rol', 3);

        return view('admin.reportes.clientes', compact('reportes', 'empleados', 'componentes', 'computadoras', 'clientes'));
    }
}
