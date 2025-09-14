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
        $query = Ensamblaje::query();

        // Filtro por empleado (incluye "sin asignar")
        if ($request->idEmpleado === 'null') {
            $query->whereNull('idEmpleado');
        } elseif ($request->filled('idEmpleado')) {
            $query->where('idEmpleado', $request->idEmpleado);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Traer ensamblajes con relación a empleado
        $ensamblajes = $query->with('empleado')->orderBy('id', 'desc')->get();

        // Datos auxiliares para los filtros
        $empleados = User::all();
        $estados = ['vendido', 'en proceso', 'ensamblado'];

        return view('admin.ensamblajes.listar', compact('ensamblajes', 'empleados', 'estados'));
    }
}
