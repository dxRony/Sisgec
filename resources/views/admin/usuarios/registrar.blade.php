@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Registrar Usuario</h2>

    <form action="{{ route('admin.usuarios.register.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nit</label>
            <input type="number" name="id" class="form-control" value="{{ old('id') }}" required>
            @error('id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
            @error('username') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Celular</label>
            <input type="text" name="celular" class="form-control" value="{{ old('celular') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Rol</label>
            <select name="rol" class="form-select" required>
                <option value="">-- Selecciona un rol --</option>
                <option value="2" {{ old('rol')=='2' ? 'selected' : '' }}>Empleado</option>
                <option value="3" {{ old('rol')=='3' ? 'selected' : '' }}>Cliente</option>
            </select>
            @error('rol') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-success">Registrar</button>
        <a href="{{ route('admin.usuarios.listar') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection