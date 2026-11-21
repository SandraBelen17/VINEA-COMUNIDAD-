@extends('layouts.panel')

@section('titulo', $titulo)

@section('contenido')
    <div class="panel-titulo">
        <div>
            <h1>{{ $titulo }}</h1>
            <p>Este módulo está en construcción.</p>
        </div>
    </div>
@endsection
