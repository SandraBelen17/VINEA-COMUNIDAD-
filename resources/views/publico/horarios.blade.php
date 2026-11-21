@extends('layouts.publico')

@section('titulo', 'Horarios')

@php
    // Encabezado de cada semana: tipo => [etiqueta, título, ¿mostrar hora de fin?]
    $secciones = [
        'misa' => ['Misas', 'Misa en el templo parroquial', false],
        'confesion' => ['Confesiones', 'Sacramento de la reconciliación', true],
        'oficina' => ['Oficina parroquial', 'Atención en oficina', true],
    ];

    // Misas en capillas: texto fijo (la tabla horarios no guarda el lugar)
    $capillas = [
        ['Lomas de la Soledad', 'Domingo', '9:00 am'],
        ['San Francisco', 'Domingo', '10:00 am'],
        ['Santa Cruz', 'Domingo', '11:40 am'],
        ['San Judas Tadeo', 'Domingo', '5:00 pm'],
        ['Virgen de Juquila (Observatorio)', 'Domingo', '6:00 pm'],
        ['San Juan Bautista (Loma Bonita)', 'Domingo', '7:00 pm'],
        ['Señor de la Misericordia', 'Sábado', '6:00 pm'],
        ['Ermita de María de Guadalupe', 'Fechas especiales', null],
    ];
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

    @foreach ($secciones as $tipo => [$etiqueta, $titulo, $conFin])
        <section class="seccion-horario" aria-labelledby="titulo-{{ $tipo }}">
            <div class="contenedor">
                <div class="seccion-encabezado">
                    <span class="etiqueta">{{ $etiqueta }}</span>
                    <h2 id="titulo-{{ $tipo }}">{{ $titulo }}</h2>
                    @if ($tipo === 'confesion')
                        <p class="texto-suave">Todos los días de 8 a 9 pm. Los jueves también durante la hora santa.</p>
                    @endif
                </div>

                <div class="semana-desliza">
                    <ul class="semana">
                        @foreach (\App\Models\Horario::DIAS as $dia => $nombreDia)
                            <li class="dia @if ($dia === $hoy) dia-hoy @endif">
                                @if ($dia === $hoy)
                                    <span class="dia-nombre">Hoy<span class="solo-lector">, {{ $nombreDia }}</span></span>
                                @else
                                    <span class="dia-nombre"><span aria-hidden="true">{{ \App\Models\Horario::DIAS_CORTOS[$dia] }}</span><span class="solo-lector">{{ $nombreDia }}</span></span>
                                @endif

                                @forelse ($semana[$tipo][$dia] as $h)
                                    <span class="dia-hora">
                                        <span class="dia-hora-parte">{{ $h->inicio_texto }}</span>@if ($conFin) – <span class="dia-hora-parte">{{ $h->fin_texto }}</span>@endif
                                    </span>
                                    @if ($tipo === 'confesion' && $dia === 'jueves' && $h->hora_inicio === '18:00:00')
                                        <span class="dia-nota">Hora santa</span>
                                    @endif
                                @empty
                                    <span class="dia-hora dia-vacio"><span aria-hidden="true">—</span><span class="solo-lector">Sin horario</span></span>
                                @endforelse
                            </li>
                        @endforeach
                    </ul>
                </div>
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
                @foreach ($capillas as [$capilla, $cuando, $hora])
                    <li>
                        <span class="capilla-nombre">{{ $capilla }}</span>
                        <span class="texto-suave">{{ $cuando }}@if ($hora) · {{ $hora }}@endif</span>
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
