<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Computadora;
use App\Models\Componente;

class ComputadoraController extends Controller
{
    public function index()
    {
        $computadoras = Computadora::with('componentes')->get();
        return view('admin.computadoras.index', compact('computadoras'));
    }

    public function create()
    {
        $componentes = Componente::all();
        return view('admin.computadoras.create', compact('componentes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'disponibilidad' => 'required|integer|min:0',
            'personalizada' => 'required|boolean',
            'componentes' => 'required|array',
            'componentes.*' => 'exists:Componente,id',
            'cantidades.*' => 'required|integer|min:1'
        ]);

        $computadora = Computadora::create([
            'disponibilidad' => $request->disponibilidad,
            'personalizada' => $request->personalizada
        ]);

        // Guardar relación en la tabla pivot
        foreach ($request->componentes as $index => $idComponente) {
            $computadora->componentes()->attach($idComponente, [
                'cantidad' => $request->cantidades[$index]
            ]);
        }

        return redirect()->route('admin.computadoras.index')->with('success', 'Computadora registrada correctamente.');
    }

    public function edit($id)
    {
        $computadora = Computadora::with('componentes')->findOrFail($id);
        $componentes = Componente::all();
        return view('admin.computadoras.edit', compact('computadora', 'componentes'));
    }

    public function update(Request $request, $id)
    {
        $computadora = Computadora::findOrFail($id);

        $computadora->update([
            'disponibilidad' => $request->disponibilidad,
            'personalizada' => $request->personalizada
        ]);

        // Reiniciar componentes relacionados
        $computadora->componentes()->detach();

        foreach ($request->componentes as $index => $idComponente) {
            $computadora->componentes()->attach($idComponente, [
                'cantidad' => $request->cantidades[$index]
            ]);
        }

        return redirect()->route('admin.computadoras.index')->with('success', 'Computadora actualizada correctamente.');
    }
}
