@extends('layouts.cliente')

@section('content')
    <h1>Bienvenido, {{ $usuario->nombre }}</h1>
    <p>Este es tu panel como cliente.</p>
@endsection
