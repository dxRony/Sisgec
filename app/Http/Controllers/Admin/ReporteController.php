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
        //obteniendo los componentes y su total de vendidos
        $componentes = DetalleVenta::select(
            'idComponente',
            DB::raw('SUM(cantidad) as totalVendido')
        )
            ->whereNotNull('idComponente')
            ->groupBy('idComponente')
            ->with('componente')
            ->orderByDesc('totalVendido')
            ->get();
        return view('admin.reportes.componentes', compact('componentes'));
    }

    public function computadorasMasVendidas()
    {
        //obteniendo las computadoras con el id y la suma de las veces que han sido vendidas
        $computadoras = DetalleVenta::select(
            'idComputadora',
            DB::raw('SUM(cantidad) as totalVendido')
        )
            ->whereNotNull('idComputadora')
            ->groupBy('idComputadora')
            ->with('computadora.componentes')
            ->orderByDesc('totalVendido')
            ->get();
        // enviando a vista con las cantidades vendidas por computadoras
        return view('admin.reportes.computadoras', compact('computadoras'));
    }

    public function reporteClientes(Request $request)
    {
        //preparando peticion
        $query = ReporteCliente::query();
        //evaluando filtros
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
        //ordenado peticion
        $reportes = $query->orderBy('fecha', 'desc')->get();
        //obteniendo datos para los selects
        $empleados = User::all()->where('rol', 2);
        $componentes = Componente::all();
        $computadoras = Computadora::all();
        $clientes = User::all()->where('rol', 3);
        //enviando a vista con los instrumentos necesarios
        return view('admin.reportes.clientes', compact('reportes', 'empleados', 'componentes', 'computadoras', 'clientes'));
    }
}
