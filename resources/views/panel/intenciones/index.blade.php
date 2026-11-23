@extends('layouts.panel')

@section('titulo', 'Intenciones')

@php
    $vacios = [
        'pendiente' => 'No hay intenciones por revisar.',
        'publicada' => 'No hay intenciones publicadas.',
        'rechazada' => 'No hay intenciones rechazadas.',
    ];
@endphp

@section('contenido')
    <div class="panel-titulo">
        <div>
            <h1>Intenciones</h1>
            <p>Revisa las oraciones y velitas antes de que aparezcan en el muro.</p>
        </div>
    </div>

    <nav class="pestanas" aria-label="Filtrar por estado">
        @foreach (\App\Models\Intencion::ESTADOS as $clave => $nombre)
            <a href="{{ route('panel.intenciones', $clave === 'pendiente' ? [] : ['estado' => $clave]) }}"
               @if ($estado === $clave) aria-current="page" @endif>{{ $nombre }}@if ($clave === 'pendiente') ({{ $porRevisar }})@endif</a>
        @endforeach
    </nav>

    @if ($intenciones->isEmpty())
        <p class="lista-vacia texto-suave">{{ $vacios[$estado] }}</p>
    @else
        <ul class="lista-revision">
            @foreach ($intenciones as $i)
                <li>
                    <span class="revision-datos">
                        <span class="intencion-tipo"><i data-lucide="{{ $i->tipo_icono }}"></i> {{ $i->tipo_texto }}</span>
                        <span class="intencion-texto">{{ $i->contenido }}</span>
                        <span class="revision-autor">{{ $i->autor_texto }} · {{ $i->fecha_texto }}</span>
                    </span>

                    <div class="revision-acciones">
                        @unless ($i->estado === 'publicada')
                            <form method="POST" action="{{ route('panel.intenciones.publicar', $i) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="boton-chico boton-chico-ok">Publicar</button>
                            </form>
                        @endunless
                        @unless ($i->estado === 'rechazada')
                            <form method="POST" action="{{ route('panel.intenciones.rechazar', $i) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="boton-chico boton-chico-peligro">Rechazar</button>
                            </form>
                        @endunless
                        <form method="POST" action="{{ route('panel.intenciones.eliminar', $i) }}"
                              onsubmit="return confirm('¿Eliminar esta intención? No se puede deshacer.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="boton-texto enlace enlace-peligro">Eliminar</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>

        @include('partials.paginas', ['paginas' => $intenciones])
    @endif
@endsection
