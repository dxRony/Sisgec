@extends('layouts.admin')

@section('content')
<div class="form-container">
    <h2 class="mb-4">Registrar Componente</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.componentes.registrar.post') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tipo de Componente</label>
            <select name="tipoComponente" id="tipoComponente" class="form-select" required>
                <option value="">-- Selecciona un tipo --</option>
                <option value="Procesador">Procesador</option>
                <option value="Memoria RAM">Memoria RAM</option>
                <option value="Almacenamiento">Almacenamiento</option>
                <option value="Fuente De Poder">Fuente de Poder</option>
                <option value="Gabinete">Gabinete</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Marca</label>
            <input type="text" name="marca" class="form-control" required>
        </div>

        <div id="campos-dinamicos">
            
        </div>

        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" min="0" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Precio (Q)</label>
            <input type="number" step="0.01" name="precio" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Registrar</button>
        <a href="{{ route('admin.componentes.listar') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    document.getElementById('tipoComponente').addEventListener('change', function() {
        const tipo = this.value;
        const contenedor = document.getElementById('campos-dinamicos');
        contenedor.innerHTML = '';

        if (tipo === 'Procesador') {
            contenedor.innerHTML = `
            <div class="mb-3">
                <label class="form-label">Consumo Energético (W)</label>
                <input type="number" name="consumoEnergetico" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Núcleos</label>
                <input type="number" name="nucleos" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Velocidad (GHz)</label>
                <input type="number" name="velocidad" class="form-control" step="0.1" min="0">
            </div>
        `;
        } else if (tipo === 'Memoria RAM') {
            contenedor.innerHTML = `
        <div class="mb-3">
            <label class="form-label">Consumo Energético (W)</label>
            <input type="number" name="consumoEnergetico" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Capacidad (GB)</label>
            <input type="number" name="capacidad" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Velocidad (MHz)</label>
            <input type="number" name="velocidad" class="form-control" step="0.01">
        </div>
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select">
            <option value="">-- Selecciona tipo de RAM --</option>
            <option value="DDR2">DDR2</option>
            <option value="DDR3">DDR3</option>
            <option value="DDR4">DDR4</option>
            <option value="DDR5">DDR5</option>
            </select>
        </div>
    `;
        } else if (tipo === 'Almacenamiento') {
            contenedor.innerHTML = `
        <div class="mb-3">
            <label class="form-label">Consumo Energético (W)</label>
            <input type="number" name="consumoEnergetico" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Capacidad (GB)</label>
            <input type="number" name="capacidad" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select">
                <option value="">-- Selecciona tipo de almacenamiento --</option>
            <option value="SSD">SSD</option>
            <option value="HDD">HDD</option>
            </select>
        </div>        
    `;
        } else if (tipo === 'Fuente De Poder') {
            contenedor.innerHTML = `
            <div class="mb-3">
                <label class="form-label">Potencia (W)</label>
                <input type="number" name="potencia" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Eficiencia</label>
                <select name="eficiencia" class="form-select">
                    <option value="">-- Selecciona la eficiencia --</option>
                    <option value="White">White</option>
                    <option value="Bronze">Bronze</option>
                    <option value="Silver">Silver</option>
                    <option value="Gold">Gold</option>
                    <option value="Platinum">Platinum</option>
                    <option value="Titanium">Titanium</option>
                </select>
            </div>
        `;
        } else if (tipo === 'Gabinete') {
            contenedor.innerHTML = `
            <div class="mb-3">
                <label class="form-label">Color</label>
                <select name="color" class="form-select">
                    <option value="">-- Selecciona el color --</option>
                    <option value="Negro">Negro</option>
                    <option value="Blanco">Blanco</option>
                    <option value="Plateado">Plateado</option>
                    <option value="Rojo">Rojo</option>
                </select>                
            </div>
        `;
        }
    });
</script>
@endsection