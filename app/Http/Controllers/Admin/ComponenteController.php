<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Componente;
use Illuminate\Http\Request;

class ComponenteController extends Controller
{
    public function listar()
    {
        //obteniendo todos los componentes
        $componentes = Componente::all();
        //enviando a vista con los componentes
        return view('admin.componentes.listar', compact('componentes'));
    }

    public function registrarV()
    {
        return view('admin.componentes.registrar');
    }

    public function registrar(Request $request)
    {
        //validando datos del form
        $request->validate([
            'tipoComponente' => 'required|string|in:Procesador,Memoria RAM,Almacenamiento,Fuente De Poder,Gabinete',
            'marca' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
        ]);
        //creando componente en la DB
        Componente::create($request->all());
        //enviando a vista con msj
        return redirect()->route('admin.componentes.listar')->with('success', 'Componente registrado correctamente.');
    }

    public function editarV($id)
    {
        //obteniendo componente a editar
        $componente = Componente::findOrFail($id);
        //mandando a vista con componente
        return view('admin.componentes.editar', compact('componente'));
    }

    public function editar(Request $request, $id)
    {
        //obteniendo componente
        $componente = Componente::findOrFail($id);
        //validando datos del form
        $request->validate([
            'stock' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
        ]);
        //actualizando componente en la DB
        $componente->update($request->all());
        //mandando a vista con msj
        return redirect()->route('admin.componentes.listar')->with('success', 'Componente actualizado correctamente.');
    }

    public function eliminar($id)
    {
        //obteniendo y eliminando componente
        $componente = Componente::findOrFail($id);
        $componente->delete();
        //mandando a vista con msj
        return redirect()->route('admin.componentes.listar')->with('success', 'Componente eliminado correctamente.');
    }
}
