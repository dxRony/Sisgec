@extends('layouts.empleado')

@section('content')
<div class="form-container">
    <h2 class="mb-4">Editar Cliente</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('empleado.clientes.edit.put', $cliente->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nit: {{old('name', $cliente->id) }}</label>         
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $cliente->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" name="username" class="form-control" value="{{ old('username', $cliente->username) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Celular</label>
            <input type="text" name="celular" class="form-control" value="{{ old('celular', $cliente->celular) }}">
        </div>        

        <div class="mb-3">
            <label class="form-label">Activo</label>
            <select name="activo" class="form-select">
                <option value="1" {{ $cliente->activo ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ !$cliente->activo ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Guardar cambios</button>
        <a href="{{ route('empleado.clientes.listar') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
