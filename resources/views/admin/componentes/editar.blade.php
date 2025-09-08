@extends('layouts.admin')

@section('content')
<div class="form-container">
    <h2 class="mb-4">Editar Componente</h2>

    <form action="{{ route('admin.componentes.update', $componente->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock', $componente->stock) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control" value="{{ old('precio', $componente->precio) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('admin.componentes.listar') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
