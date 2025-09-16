@extends('layouts.cliente')

@section('content')
<div class="container mt-4">
    <h2 class="lbl-1">Atención al Cliente - Queja o Sugerencia</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('cliente.reportes.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tipo de reporte</label>
            <select name="tipo" class="form-select" required>
                <option value="">-- Selecciona --</option>
                <option value="queja">Queja</option>
                <option value="sugerencia">Sugerencia</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción (maximo 200 caracteres)</label>
            <textarea name="descripcion" class="form-control" rows="4" maxlength="200" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Empleado involucrado (opcional)</label>
            <select name="idEmpleado" class="form-select">
                <option value="">-- Selecciona un empleado --</option>
                @foreach($empleados as $empleado)
                <option value="{{ $empleado->id }}">{{ $empleado->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Componente involucrado (opcional)</label>
            <select name="idComponente" class="form-select">
                <option value="">-- Selecciona un componente --</option>
                @foreach($componentes as $componente)
                <option value="{{ $componente->id }}">{{ $componente->tipoComponente }} - {{ $componente->marca }} - Q.{{ $componente->precio }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Computadora involucrada (opcional)</label>
            <select name="idComputadora" class="form-select">
                <option value="">-- Selecciona una computadora --</option>
                @foreach($computadoras as $computadora)
                <option value="{{ $computadora->id }}">ID: {{ $computadora->id }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Enviar Reporte</button>
    </form>
</div>
@endsection