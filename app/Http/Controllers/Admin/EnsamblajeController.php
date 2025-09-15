<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ensamblaje;
use App\Models\User;

class EnsamblajeController extends Controller
{
    public function listar(Request $request)
    {
        //preparabdo peticion a db
        $query = Ensamblaje::query();
        // evaluando filtro de empleado a cargo
        if ($request->idEmpleado === 'null') {
            $query->whereNull('idEmpleado');
        } elseif ($request->filled('idEmpleado')) {
            $query->where('idEmpleado', $request->idEmpleado);
        }
        // evaluando filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        //obteniendo los ensamblajes
        $ensamblajes = $query->with('empleado')->orderBy('id', 'desc')->get();
        // obteniendo los empleados para el select
        $empleados = User::where('rol', 2)->get();
        //definiendo estados de los ensamblajes
        $estados = ['vendido', 'en proceso', 'ensamblado'];
        //enviando a vista con los ensamblajes, empleados y estados
        return view('admin.ensamblajes.listar', compact('ensamblajes', 'empleados', 'estados'));
    }
}
