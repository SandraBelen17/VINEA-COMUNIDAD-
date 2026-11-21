@extends('layouts.panel')

@section('titulo', 'Resumen')

@section('contenido')
    <div class="panel-titulo">
        <div>
            <h1>Hola, {{ $saludo }}</h1>
            <p>Esto es lo que está pendiente en la parroquia.</p>
        </div>
    </div>

    <ul class="pendientes">
        @foreach ($pendientes as $p)
            <li>
                <span class="pendiente-dato">
                    <span class="pendiente-numero">{{ $p['numero'] }}</span>
                    <span class="pendiente-texto">{{ $p['texto'] }}</span>
                </span>
                <a href="{{ route($p['ruta']) }}" class="enlace">{{ $p['enlace'] }}</a>
            </li>
        @endforeach
    </ul>
@endsection
