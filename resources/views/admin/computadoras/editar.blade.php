@extends('layouts.admin')

@section('content')
<div class="form-container">
    <h2 class="mb-4">Modificar existencias</h2>

    <form action="{{ route('admin.computadoras.update', $computadora->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="disponibilidad" class="form-label">Disponibilidad</label>
            <input type="number" name="disponibilidad" class="form-control" value="{{ $computadora->disponibilidad }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Confirmar</button>
        <a href="{{ route('admin.computadoras.listar') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection