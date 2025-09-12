@extends('layouts.cliente')

@section('content')
<div class="container">
    <h2 class="lbl-1">Lista de Componentes</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Consumo Energetico(W)</th>
                <th>Nucleos</th>
                <th>Velocidad</th>
                <th>Capacidad(GB)</th>
                <th>Tipo</th>
                <th>Potencia(W)</th>
                <th>Eficencia</th>
                <th>Color</th>
                <th>Stock</th>
                <th>Precio</th> 
                <th>Acción</th>               
            </tr>
        </thead>
        <tbody>
            @foreach ($componentes as $componente)
            <tr>
                <td>{{ $componente->id }}</td>
                <td>{{ $componente->tipoComponente }}</td>
                <td>{{ $componente->marca }}</td>
                <td>{{ $componente->consumoEnergetico ?? '-' }}</td>
                <td>{{ $componente->nucleos ?? '-' }}</td>
                @if($componente->tipoComponente == 'Procesador')
                    <td>{{ $componente->velocidad }} GHz</td>
                @elseif($componente->tipoComponente == 'Memoria RAM')
                    <td>{{ $componente->velocidad }} MHz</td>
                @else 
                    <td>{{ $componente->velocidad ?? '-' }}</td>
                @endif                
                <td>{{ $componente->capacidad ?? '-' }}</td>
                <td>{{ $componente->tipo ?? '-' }}</td>
                <td>{{ $componente->potencia ?? '-' }}</td>
                <td>{{ $componente->eficiencia ?? '-' }}</td>
                <td>{{ $componente->color ?? '-' }}</td>
                <td>{{ $componente->stock }}</td>
                <td>Q.{{ $componente->precio }}</td>
                <td>
                    <form action="{{ route('cliente.carrito.agregarComponente', $componente->id) }}" method="POST" class="d-flex">
                        @csrf
                        <input type="number" name="cantidad" value="1" min="1" max="{{ $componente->stock }}" class="form-control form-control-sm me-2" style="width: 80px;" />
                        <button type="submit" class="btn btn-sm btn-success">Agregar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
