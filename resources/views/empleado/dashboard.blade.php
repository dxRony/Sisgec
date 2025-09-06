@extends('layouts.empleado')

@section('content')
    <h1>Bienvenido, {{ Auth::user()->nombre }}</h1>
    <p>Este es tu panel como empleado.</p>
@endsection
