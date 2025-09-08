@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="lbl-1">Lista de Usuarios</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Usuario</th>
                <th>Celular</th>
                <th>Rol</th>
                <th>Activo</th>
                <th>Accion</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->id }}</td>
                <td>{{ $usuario->name }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ $usuario->username }}</td>
                <td>{{ $usuario->celular }}</td>
                <td>@if ($usuario->rol == 1) Administrador
                    @elseif ($usuario->rol == 2) Empleado
                    @elseif ($usuario->rol == 3) Cliente
                    @endif</td>
                <td>{{ $usuario->activo ? 'Si' : 'No' }}</td>
                <td>@if ($usuario->id !== auth()->user()->id)
                    <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="btn btn-primary btn-sm">Editar</a>
                    @else
                    <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="btn btn-primary btn-sm">Editar (Tú)</a>
                    @endif</td>                    
            </tr>            
            @endforeach
        </tbody>
    </table>
</div>
@endsection