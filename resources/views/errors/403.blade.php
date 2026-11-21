@extends('layouts.publico')

@section('titulo', 'Sin permiso')

@section('contenido')
    <div class="contenedor">
        <div class="construccion">
            <span class="etiqueta">Error 403</span>
            <h1>No tienes permiso para entrar aquí</h1>
            <p class="texto-suave">{{ $exception->getMessage() ?: 'Esta sección es solo para otro tipo de usuario.' }}</p>
            <p>
                @auth
                    <a href="{{ auth()->user()->rutaInicio() }}" class="enlace">Ir a mi panel</a>
                @else
                    <a href="{{ route('login') }}" class="enlace">Iniciar sesión</a>
                @endauth
            </p>
        </div>
    </div>
@endsection
