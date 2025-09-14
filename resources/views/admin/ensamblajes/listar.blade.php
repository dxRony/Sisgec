@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="lbl-1">Lista de ensamblajes</h2>

    {{-- Mensajes --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Formulario de filtros --}}
    <form method="GET" action="{{ route('admin.ensamblajes.listar') }}" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <label for="idEmpleado">Empleado encargado</label>
                <select name="idEmpleado" id="idEmpleado" class="form-select">
                    <option value="">-- Todos --</option>
                    @foreach ($empleados as $empleado)
                        <option value="{{ $empleado->id }}" {{ request('idEmpleado') == $empleado->id ? 'selected' : '' }}>
                            {{ $empleado->name }}
                        </option>
                    @endforeach
                    <option value="null" {{ request('idEmpleado') === 'null' ? 'selected' : '' }}>
                        Sin asignar
                    </option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="estado">Estado</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="">-- Todos --</option>
                    @foreach ($estados as $estado)
                        <option value="{{ $estado }}" {{ request('estado') == $estado ? 'selected' : '' }}>
                            {{ ucfirst($estado) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 align-self-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('admin.ensamblajes.listar') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </div>
    </form>

    {{-- Tabla de ensamblajes --}}
    @if($ensamblajes->isEmpty())
        <div class="alert alert-info">
            No hay ensamblajes que coincidan con los filtros.
        </div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Venta No.</th>
                    <th>Computadora No.</th>
                    <th>Estado</th>
                    <th>Empleado a cargo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ensamblajes as $ensamblaje)
                <tr>
                    <td>{{ $ensamblaje->id }}</td>
                    <td>{{ $ensamblaje->idVenta }}</td>
                    <td>{{ $ensamblaje->idComputadora }}</td>
                    <td>{{ ucfirst($ensamblaje->estado) }}</td>
                    <td>{{ $ensamblaje->empleado->name ?? 'No asignado' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
