@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Registrar Computadora</h2>

    <form action="{{ route('computadoras.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Disponibilidad (inventario)</label>
            <input type="number" name="disponibilidad" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Personalizada</label>
            <select name="personalizada" class="form-select" required>
                <option value="0">No</option>
                <option value="1">Sí</option>
            </select>
        </div>

        <h4>Componentes</h4>
        @foreach($componentes as $componente)
            <div class="row mb-2">
                <div class="col-md-6">
                    <input type="checkbox" name="componentes[]" value="{{ $componente->id }}">
                    {{ $componente->tipoComponente }} - {{ $componente->marca }}
                </div>
                <div class="col-md-6">
                    <input type="number" name="cantidades[]" class="form-control" placeholder="Cantidad">
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
@endsection
