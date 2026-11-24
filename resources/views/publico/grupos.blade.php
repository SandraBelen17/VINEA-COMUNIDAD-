@extends('layouts.publico')

@section('titulo', 'Grupos')

@section('contenido')
    <section class="portada">
        <img src="{{ asset('img/altar.jpg') }}" alt="" style="object-position: center 40%">
        <div class="slider-velo" aria-hidden="true"></div>
        <div class="portada-texto">
            <span class="etiqueta">Comunidad</span>
            <h1>Grupos parroquiales</h1>
            <p>Encuentra un grupo y súmate a la vida de la parroquia.</p>
        </div>
    </section>

    <section class="seccion-grupos">
        <div class="contenedor">
            @if ($grupos->isEmpty())
                <p class="grupos-vacio texto-suave">Por ahora no hay grupos activos.</p>
            @else
                <ul class="lista-grupos">
                    @foreach ($grupos as $g)
                        <li>
                            <h2 class="grupo-nombre">{{ $g->nombre }}</h2>
                            <div class="grupo-datos">
                                @if ($g->descripcion)
                                    <p class="grupo-descripcion texto-suave">{{ $g->descripcion }}</p>
                                @endif
                                @if ($g->detalle_texto)
                                    <p class="grupo-detalle">{{ $g->detalle_texto }}</p>
                                @endif
                                @if ($g->coordinador || $g->whatsapp_url)
                                    <div class="grupo-contacto">
                                        @if ($g->coordinador)
                                            <p class="grupo-coordinador">Coordina: {{ $g->coordinador }}</p>
                                        @endif
                                        @if ($g->whatsapp_url)
                                            <a href="{{ $g->whatsapp_url }}" class="boton boton-linea boton-whatsapp" target="_blank" rel="noopener">
                                                <i data-lucide="message-circle"></i> Escribir por WhatsApp
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif

            <a href="{{ route('contacto') }}" class="enlace grupos-unirte">¿Quieres unirte? Escríbenos</a>
        </div>
    </section>
@endsection
