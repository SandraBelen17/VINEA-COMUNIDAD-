@extends('layouts.panel')

@section('titulo', 'Citas')

@php
    $vacios = [
        'pendiente' => 'No hay citas pendientes.',
        'aprobada' => 'No hay citas aprobadas.',
        'rechazada' => 'No hay citas rechazadas.',
        'todas' => 'Todavía no hay citas.',
    ];

    // Pestañas: los estados del ENUM y al final "Todas"
    $pestanas = collect(\App\Models\Cita::ESTADOS)->map(fn ($e) => $e[1])->put('todas', 'Todas');
@endphp

@section('contenido')
    <div class="panel-titulo">
        <div>
            <h1>Citas</h1>
            <p>Solicitudes de los feligreses.</p>
        </div>
    </div>

    @unless ($puedeDecidir)
        <p class="aviso aviso-info">Puedes ver las citas, pero solo el párroco las aprueba o las rechaza.</p>
    @endunless

    <nav class="pestanas" aria-label="Filtrar por estado">
        @foreach ($pestanas as $clave => $nombre)
            <a href="{{ route('panel.citas', $clave === 'pendiente' ? [] : ['estado' => $clave]) }}"
               @if ($estado === $clave) aria-current="page" @endif>{{ $nombre }}@if ($clave === 'pendiente') ({{ $pendientes }})@endif</a>
        @endforeach
    </nav>

    <div class="tabla-desliza">
        <table class="tabla tabla-citas">
            <thead>
                <tr>
                    <th scope="col">Feligrés</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Hora</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Motivo</th>
                    <th scope="col">Estado</th>
                    @if ($puedeDecidir)
                        <th scope="col"><span class="solo-lector">Acciones</span></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($citas as $c)
                    <tr>
                        <td>{{ $c->usuario?->name ?? '—' }}</td>
                        <td class="sin-cortar">{{ $c->fecha_texto }}</td>
                        <td class="sin-cortar">{{ $c->hora_texto }}</td>
                        <td>{{ $c->tipo_texto }}</td>
                        <td class="cita-motivo">{{ $c->motivo }}</td>
                        <td><span class="estado {{ $c->estado_clase }}">{{ $c->estado_texto }}</span></td>
                        @if ($puedeDecidir)
                            <td>
                                @if ($c->estado === 'pendiente')
                                    <div class="revision-acciones">
                                        <form method="POST" action="{{ route('panel.citas.aprobar', $c) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="boton-chico boton-chico-ok">Aprobar</button>
                                        </form>
                                        <form method="POST" action="{{ route('panel.citas.rechazar', $c) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="boton-chico boton-chico-peligro">Rechazar</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="texto-suave" aria-label="Sin acciones">—</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $puedeDecidir ? 7 : 6 }}" class="tabla-vacia">{{ $vacios[$estado] }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.paginas', ['paginas' => $citas, 'anterior' => 'Página anterior', 'siguiente' => 'Página siguiente'])
@endsection
