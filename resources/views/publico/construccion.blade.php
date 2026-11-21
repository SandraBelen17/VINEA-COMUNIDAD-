@extends('layouts.publico')

@section('titulo', $titulo)

@section('contenido')
    <div class="contenedor">
        <div class="construccion">
            <span class="etiqueta">En construcción</span>
            <h1>{{ $titulo }}</h1>
            <p class="texto-suave">Esta sección estará lista muy pronto.</p>
            <p><a href="{{ route('inicio') }}" class="enlace">Volver al inicio</a></p>
        </div>
    </div>
@endsection
