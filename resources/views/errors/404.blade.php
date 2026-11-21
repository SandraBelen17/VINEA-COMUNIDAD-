@extends('layouts.publico')

@section('titulo', 'Página no encontrada')

@section('contenido')
    <div class="contenedor">
        <div class="construccion">
            <span class="etiqueta">Error 404</span>
            <h1>No encontramos esta página</h1>
            <p class="texto-suave">Puede que el enlace esté mal escrito o que la página ya no exista.</p>
            <p><a href="{{ route('inicio') }}" class="enlace">Volver al inicio</a></p>
        </div>
    </div>
@endsection
