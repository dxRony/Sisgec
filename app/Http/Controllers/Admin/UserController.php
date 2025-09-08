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
        $usuarios = User::all();
        return view('admin.usuarios.listar', compact('usuarios'));
    }

    public function registrarV()
    {
        return view('admin.usuarios.registrar');
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|unique:users,id',
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|min:6|confirmed',
            'celular' => 'nullable|string|max:20',
            'rol' => 'required|in:2,3',
        ]);

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
        return redirect()->route('admin.usuarios.listar')->with('success', 'Usuario registrado correctamente.');
    }

    public function editarV($id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.usuarios.editar', compact('usuario'));
    }

    public function editar(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $usuario->id,
            'celular' => 'nullable|string|max:20',
            'activo' => 'required|boolean',
        ]);

        $usuario->update([
            'name' => $request->name,
            'username' => $request->username,
            'celular' => $request->celular,
            'activo' => $request->activo,
        ]);
        return redirect()->route('admin.usuarios.listar')->with('success', 'Usuario actualizado correctamente.');
    }
}
