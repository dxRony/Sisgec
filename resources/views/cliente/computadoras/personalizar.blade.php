@extends('layouts.cliente')

@section('content')
<div class="container">
    <h2 class="lbl-1">Personalizar Computadora ID: {{ $computadora->id }}</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <form action="{{ route('cliente.computadoras.personalizar.post', $computadora->id) }}" method="POST">
        @csrf
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Tipo</th>
                    <th>Componente</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($agrupados as $tipo => $componentesDisponibles)
                <tr>
                    <td><strong>{{ $tipo }}</strong></td>
                    <td>
                        <select name="componentes[{{ $tipo }}][id]" class="form-select" required>
                            @foreach($componentesDisponibles as $componente)
                            <option value="{{ $componente->id }}"
                                {{ isset($seleccionados[$tipo]) && $seleccionados[$tipo]->id == $componente->id ? 'selected' : '' }}>
                                {{ $componente->marca }}
                                @if($tipo === 'Procesador')
                                - {{ $componente->velocidad }} GHz / {{ $componente->nucleos }} núcleos
                                @elseif($tipo === 'Memoria RAM')
                                - {{ $componente->capacidad }} GB / {{ $componente->velocidad }} MHz
                                @elseif($tipo === 'Almacenamiento')
                                - {{ $componente->capacidad }} GB / {{ $componente->tipo }}
                                @elseif($tipo === 'Fuente De Poder')
                                - {{ $componente->potencia }} W
                                @elseif($tipo === 'Gabinete')
                                - Color: {{ $componente->color }}
                                @endif
                            </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        @if($tipo === 'Memoria RAM' || $tipo === 'Almacenamiento')
                        <input type="number"
                            name="componentes[{{ $tipo }}][cantidad]"
                            value="{{ $seleccionados[$tipo]->pivot->cantidad ?? 1 }}"
                            min="1" class="form-control" />
                        @else
                        1
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-success">Agregar al carrito</button>
        <a href="{{ route('cliente.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection