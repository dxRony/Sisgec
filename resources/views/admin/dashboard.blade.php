@extends('layouts.admin')

@section('content')
    <h1>Bienvenido, {{ Auth::user()->nombre }}</h1>
    <p>Este es tu panel como administrador.</p>
@endsection
