@extends('layouts.publico')

@section('titulo', 'Horarios')

@php
    // Encabezado de cada semana: tipo => [etiqueta, título]
    $secciones = [
        'misa' => ['Misas', 'Misa en el templo parroquial'],
        'confesion' => ['Confesiones', 'Sacramento de la reconciliación'],
        'oficina' => ['Oficina parroquial', 'Atención en oficina'],
    ];
    // Las capillas ($capillas) vienen de config/parroquia.php (las mismas que en Inicio)
@endphp

@section('contenido')
    <section class="portada">
        <img src="{{ asset('img/interior.jpg') }}" alt="" style="object-position: center 55%">
        <div class="slider-velo" aria-hidden="true"></div>
        <div class="portada-texto">
            <span class="etiqueta">Horarios</span>
            <h1>Horarios de la parroquia</h1>
            <p>Misas, confesiones y atención en oficina durante la semana.</p>
        </div>
    </section>

    @foreach ($secciones as $tipo => [$etiqueta, $titulo])
        <section class="seccion-horario" aria-labelledby="titulo-{{ $tipo }}">
            <div class="contenedor">
                <div class="seccion-encabezado">
                    <span class="etiqueta">{{ $etiqueta }}</span>
                    <h2 id="titulo-{{ $tipo }}">{{ $titulo }}</h2>
                    @if ($tipo === 'confesion')
                        <p class="texto-suave">Todos los días de 8 a 9 pm. Los jueves también durante la hora santa.</p>
                    @endif
                </div>

                @include('partials.semana', ['tipo' => $tipo, 'horas' => $semana[$tipo], 'hoy' => $hoy])
            </div>
        </section>
    @endforeach

    <section class="seccion-horario" aria-labelledby="titulo-capillas">
        <div class="contenedor">
            <div class="seccion-encabezado">
                <span class="etiqueta">Capillas</span>
                <h2 id="titulo-capillas">Misa en las capillas</h2>
            </div>

            <ul class="lista-capillas">
                @foreach ($capillas as $c)
                    <li>
                        <span class="capilla-nombre">{{ $c['nombre'] }}@if ($c['zona']) ({{ $c['zona'] }})@endif</span>
                        <span class="texto-suave">{{ $c['dia'] }}@if ($c['hora']) · {{ $c['hora'] }}@endif</span>
                    </li>
                @endforeach
            </ul>

            <div class="nota-final">
                <p class="texto-suave">En las capillas la misa es los domingos, salvo las excepciones indicadas.</p>
                <a href="{{ route('inicio') }}#capillas" class="enlace">Ver capillas y sectores</a>
            </div>
        </div>
    </section>
@endsection
