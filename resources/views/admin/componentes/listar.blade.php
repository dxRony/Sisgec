@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="lbl-1">Lista de Componentes</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
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
                <th>Accion</th>
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
                @if($componente-> tipoComponente == 'Procesador')
                <td>{{ $componente->velocidad }} GHz</td>
                @elseif($componente-> tipoComponente == 'Memoria RAM')
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
                <td>{{ $componente->precio }}</td>
                <td>
                    <a href="{{ route('admin.componentes.edit', $componente->id) }}" 
                       class="btn btn-warning btn-sm">Editar</a>

                    <form action="{{ route('admin.componentes.destroy', $componente->id) }}" 
                          method="POST" 
                          style="display:inline-block;"
                          onsubmit="return confirm('Eliminar el componente: {{$componente->tipoComponente}}, en caso de tener datos incorrectos');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
