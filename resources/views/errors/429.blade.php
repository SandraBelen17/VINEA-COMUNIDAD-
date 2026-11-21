@extends('layouts.publico')

@section('titulo', 'Demasiados intentos')

@section('contenido')
    <div class="contenedor">
        <div class="construccion">
            <span class="etiqueta">Espera un momento</span>
            <h1>Demasiados intentos</h1>
            <p class="texto-suave">Por seguridad, espera un minuto antes de volver a intentar iniciar sesión.</p>
            <p><a href="{{ route('login') }}" class="enlace">Volver a iniciar sesión</a></p>
        </div>
    </div>
@endsection
