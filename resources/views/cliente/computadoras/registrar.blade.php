@extends('layouts.cliente')

@section('content')
<div class="form-container">
    <h2 class="mb-4">Armar computadora desde cero</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('cliente.computadoras.register.post') }}" method="POST">
        @csrf
        <h4>Selecciona sus componentes</h4>
        <div class="mb-3">
            <label class="form-label">Procesador</label>
            <select name="componentes[procesador]" class="form-select">
                <option value="">-- Seleccionar procesador --</option>
                @foreach($procesadores as $procesador)
                <option value="{{ $procesador->id }}">{{ $procesador->marca }} - {{ $procesador->velocidad }} GHz</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Fuente de Poder</label>
            <select name="componentes[fuente]" class="form-select">
                <option value="">-- Seleccionar fuente de poder --</option>
                @foreach($fuentes as $fuente)
                <option value="{{ $fuente->id }}">{{ $fuente->marca }} - {{ $fuente->potencia }}W</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Gabinete</label>
            <select name="componentes[gabinete]" class="form-select">
                <option value="">-- Seleccionar gabinete --</option>
                @foreach($gabinetes as $gabinete)
                <option value="{{ $gabinete->id }}">{{ $gabinete->marca }} - {{ $gabinete->color }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Memoria RAM</label>
            @foreach($rams as $ram)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="componentes[rams][{{ $ram->id }}]" value="1">
                <label class="form-check-label">
                    {{ $ram->marca }} - {{ $ram->capacidad }}GB - {{ $ram->tipo }}
                </label>
                <input type="number" name="cantidades[rams][{{ $ram->id }}]" min="1" placeholder="Cantidad">
            </div>
            @endforeach
        </div>
        <div class="mb-3">
            <label class="form-label">Almacenamiento</label>
            @foreach($storages as $storage)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="componentes[storages][{{ $storage->id }}]" value="1">
                <label class="form-check-label">
                    {{ $storage->marca }} - {{ $storage->capacidad }}GB - {{ $storage->tipo }}
                </label>
                <input type="number" name="cantidades[storages][{{ $storage->id }}]" min="1" placeholder="Cantidad">
            </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary">Agregar al carrito</button>
        <a href="{{ route('cliente.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection