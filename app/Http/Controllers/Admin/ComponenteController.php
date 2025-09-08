<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Componente;
use Illuminate\Http\Request;

class ComponenteController extends Controller
{
    public function listar()
    {
        $componentes = Componente::all();
        return view('admin.componentes.listar', compact('componentes'));
    }

    public function registrarV()
    {
        return view('admin.componentes.registrar');
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'tipoComponente' => 'required|string|in:Procesador,Memoria RAM,Almacenamiento,Fuente De Poder,Gabinete',
            'marca' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
        ]);

        Componente::create($request->all());

        return redirect()->route('admin.componentes.listar')->with('success', 'Componente registrado correctamente.');
    }

    public function editarV($id)
    {
        $componente = Componente::findOrFail($id);
        return view('admin.componentes.editar', compact('componente'));
    }

    public function editar(Request $request, $id)
    {
        $componente = Componente::findOrFail($id);

        $request->validate([
            'stock' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
        ]);

        $componente->update($request->all());

        return redirect()->route('admin.componentes.listar')
            ->with('success', 'Componente actualizado correctamente.');
    }

    public function eliminar($id)
{
    $componente = Componente::findOrFail($id);
    $componente->delete();

    return redirect()->route('admin.componentes.listar')
                     ->with('success', 'Componente eliminado correctamente.');
}
}
