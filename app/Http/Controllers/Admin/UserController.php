<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        //obteniendo todos los usuarios y mandando a la vista
        $usuarios = User::all();
        return view('admin.usuarios.listar', compact('usuarios'));
    }

    public function registrarV()
    {
        return view('admin.usuarios.registrar');
    }

    public function registrar(Request $request)
    {
        //validando datos del form
        $request->validate([
            'id' => 'required|numeric|unique:users,id',
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:20',
            'password' => 'required|min:6|confirmed',
            'celular' => 'nullable|string|max:20',
            'rol' => 'required|in:2,3',
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
            'rol' => $request->rol,
        ]);
        //retornando con msj de finalizadion
        return redirect()->route('admin.usuarios.listar')->with('success', 'Usuario registrado correctamente.');
    }

    public function editarV($id)
    {
        //obteniendo al usuario con el id seleccionado
        $usuario = User::findOrFail($id);
        //mandando a la vista con el usuario seleccionado
        return view('admin.usuarios.editar', compact('usuario'));
    }

    public function editar(Request $request, $id)
    {
        //obteniendo el usuario
        $usuario = User::findOrFail($id);
        //validando datos del form
        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|unique:users,username,' . $usuario->id,
            'celular' => 'nullable|string|max:20',
            'activo' => 'required|boolean',
        ]);
        //haciendo actualizacion en la DB
        $usuario->update([
            'name' => $request->name,
            'username' => $request->username,
            'celular' => $request->celular,
            'activo' => $request->activo,
        ]);
        //regrando con mensaje de finalizacion
        return redirect()->route('admin.usuarios.listar')->with('success', 'Usuario actualizado correctamente.');
    }
}
