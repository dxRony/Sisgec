@extends('layouts.empleado')

@section('content')
<div class="container">
    <h2 class="lbl-1">Lista de clientes</h2>

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
                <th>Activo</th>
                <th>Accion</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clientes as $cliente)
            <tr>
                <td>{{ $cliente->id }}</td>
                <td>{{ $cliente->name }}</td>
                <td>{{ $cliente->email }}</td>
                <td>{{ $cliente->username }}</td>
                <td>{{ $cliente->celular }}</td>
                <td>{{ $cliente->activo ? 'Si' : 'No' }}</td>
                <td><a href="{{ route('empleado.clientes.edit', $cliente->id) }}" class="btn btn-primary btn-sm">Editar</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection