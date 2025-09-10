<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function listar()
    {
        $clientes = User::where('rol', 3)->get();
        return view('empleado.clientes.listar', compact('clientes'));
    }

    public function registrarV()
    {
        return view('empleado.clientes.registrar');
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|unique:users,id',
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|min:6|confirmed',
            'celular' => 'nullable|string|max:20',
        ]);

        User::create([
            'id' => $request->id,
            'name' => $request->name,
            'email' => $request->username . '@sisgec.com',
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'celular' => $request->celular,
            'activo' => true,
            'rol' => 3,
        ]);
        return redirect()->route('empleado.clientes.listar')->with('success', 'Cliente registrado correctamente.');
    }

    public function editarV($id)
    {
        $cliente = User::findOrFail($id);
        return view('empleado.clientes.editar', compact('cliente'));
    }

    public function editar(Request $request, $id)
    {
        $cliente = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $cliente->id,
            'celular' => 'nullable|string|max:20',
            'activo' => 'required|boolean',
        ]);

        $cliente->update([
            'name' => $request->name,
            'username' => $request->username,
            'celular' => $request->celular,
            'activo' => $request->activo,
        ]);
        return redirect()->route('empleado.clientes.listar')->with('success', 'Cliente actualizado correctamente.');
    }
}
