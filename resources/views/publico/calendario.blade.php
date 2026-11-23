@extends('layouts.publico')

@section('titulo', 'Calendario')

@section('contenido')
    <section class="portada">
        <img src="{{ asset('img/fachada.jpg') }}" alt="" style="object-position: center 60%">
        <div class="slider-velo" aria-hidden="true"></div>
        <div class="portada-texto">
            <span class="etiqueta">Calendario</span>
            <h1>Eventos de la comunidad</h1>
            <p>Celebraciones, reuniones y actividades de la parroquia.</p>
        </div>
    </section>

    <section class="seccion-calendario" aria-labelledby="titulo-mes">
        <div class="contenedor">
            <div class="calendario-encabezado">
                <h2 id="titulo-mes">{{ $titulo }}</h2>
                <nav class="botones" aria-label="Cambiar de mes">
                    <a href="{{ route('calendario', ['mes' => $anterior]) }}" class="boton boton-linea">Mes anterior</a>
                    <a href="{{ route('calendario', ['mes' => $siguiente]) }}" class="boton boton-linea">Mes siguiente</a>
                </nav>
            </div>

            @if ($eventos->isEmpty())
                <p class="calendario-vacio texto-suave">No hay eventos publicados en {{ mb_strtolower($titulo) }}.</p>
            @else
                <ul class="lista-eventos">
                    @foreach ($eventos as $e)
                        @php($fecha = \Illuminate\Support\Carbon::parse($e->fecha_inicio))
                        <li>
                            <span class="evento-fecha">
                                <span class="evento-dia">{{ $fecha->day }}</span>
                                <span class="evento-mes" aria-hidden="true">{{ \App\Models\Evento::MESES_CORTOS[$fecha->month] }}</span>
                                <span class="solo-lector">de {{ \App\Models\Evento::MESES[$fecha->month] }}</span>
                            </span>
                            <span class="evento-datos">
                                <span class="evento-titulo">{{ $e->titulo }}</span>
                                @if ($e->descripcion)
                                    <span class="evento-descripcion texto-suave">{{ $e->descripcion }}</span>
                                @endif
                                @if ($e->detalle_texto)
                                    <span class="evento-detalle">{{ $e->detalle_texto }}</span>
                                @endif
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
@endsection
