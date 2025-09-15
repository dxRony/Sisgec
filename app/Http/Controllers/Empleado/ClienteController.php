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
        //obteniendo usuarios con rol 3 = cliente
        $clientes = User::where('rol', 3)->get();
        //enviando a vista con msj y lista de clientes
        return view('empleado.clientes.listar', compact('clientes'));
    }

    public function registrarV()
    {
        //enviando a vista de registro de cliente
        return view('empleado.clientes.registrar');
    }

    public function registrar(Request $request)
    {
        //validando datos del form
        $request->validate([
            'id' => 'required|numeric|unique:users,id',
            'name' => 'required|string|max:20',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|min:6|confirmed',
            'celular' => 'nullable|string|max:20',
        ]);
        //creando usuario en la DB
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
        //enviando a vista de lista de usuarios con msj
        return redirect()->route('empleado.clientes.listar')->with('success', 'Cliente registrado correctamente.');
    }

    public function editarV($id)
    {
        //obteniendo usuario cliente con el id seleccionado
        $cliente = User::findOrFail($id);
        //enviando a vista con cliente recuperado
        return view('empleado.clientes.editar', compact('cliente'));
    }

    public function editar(Request $request, $id)
    {
        //recuperando usuario
        $cliente = User::findOrFail($id);
        //validando datos del form
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:20|unique:users,username,' . $cliente->id,
            'celular' => 'nullable|string|max:20',
            'activo' => 'required|boolean',
        ]);
        //actualizadndo usuario en la DB
        $cliente->update([
            'name' => $request->name,
            'username' => $request->username,
            'celular' => $request->celular,
            'activo' => $request->activo,
        ]);
        //regresando a listado de clientes con msj
        return redirect()->route('empleado.clientes.listar')->with('success', 'Cliente actualizado correctamente.');
    }
}
