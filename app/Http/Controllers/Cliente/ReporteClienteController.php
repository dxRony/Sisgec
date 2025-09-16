<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReporteCliente;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Componente;
use App\Models\Computadora;

class ReporteClienteController extends Controller
{
    public function create()
    {   
        //obtener todos los empleados (User rol = 2), componentes y computadoras
        $empleados = User::all()->where('rol', 2);
        $componentes = Componente::all();
        $computadoras = Computadora::all();
        //enviando a vista con lo obtenido
        return view('cliente.reportes.create', compact('empleados', 'componentes', 'computadoras'));
    }

    public function store(Request $request)
    {
        //validando datos del form en la DB
        $request->validate([
            'tipo' => 'required|in:queja,sugerencia',
            'descripcion' => 'required|string|max:200',
        ]);
        //creando reporte en la DB
        ReporteCliente::create([
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'idEmpleado' => $request->idEmpleado,
            'idComponente' => $request->idComponente,
            'idComputadora' => $request->idComputadora,
            'nitUsuario' => Auth::id(),
        ]);
        //regresando a vista con msj
        return redirect()->route('cliente.reportes.create')
            ->with('success', 'Tu reporte ha sido enviado correctamente, de ser necesario un empleado se pondra en contacto contigo.');
    }
}
