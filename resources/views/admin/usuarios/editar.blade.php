@extends('layouts.admin')

@section('content')
<div class="form-container">
    <h2 class="mb-4">Editar Usuario</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.usuarios.edit.put', $usuario->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nit: {{old('name', $usuario->id) }}</label>         
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" name="username" class="form-control" value="{{ old('username', $usuario->username) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Celular</label>
            <input type="text" name="celular" class="form-control" value="{{ old('celular', $usuario->celular) }}">
        </div>        

        <div class="mb-3">
            <label class="form-label">Activo</label>
            <select name="activo" class="form-select">
                <option value="1" {{ $usuario->activo ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ !$usuario->activo ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Guardar cambios</button>
        <a href="{{ route('admin.usuarios.listar') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
