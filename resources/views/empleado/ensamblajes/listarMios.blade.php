@extends('layouts.empleado')

@section('content')

<div class="container">
    <h2 class="lbl-1">Lista de mis ensamblajes</h2>
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($ensamblajes->isEmpty())
    <div class="alert alert-info">
        No hay ensamblajes pendientes.
    </div>
    @else
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Venta No.</th>
                <th>Computadora No.</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ensamblajes as $ensamblaje)
            <tr>
                <td>{{ $ensamblaje->id }}</td>
                <td>{{ $ensamblaje->idVenta }}</td>
                <td>{{ $ensamblaje->idComputadora }}</td>
                <td>{{ $ensamblaje->estado }}</td>
                <td><a href="{{ route('empleado.ensamblajes.ensamblar', $ensamblaje->id) }}" class="btn btn-primary btn-sm">Marcar como ensamblado</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection