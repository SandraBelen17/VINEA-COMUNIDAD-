@extends('layouts.panel')

@section('titulo', 'Mensajes')

@php
    $vacios = [
        'todos' => 'Todavía no hay mensajes.',
        'pendiente' => 'No hay mensajes sin atender.',
        'atendido' => 'No hay mensajes atendidos.',
    ];

    // Pestañas: "Todos" (de entrada, pendientes primero) y luego los estados del ENUM
    $pestanas = collect(['todos' => 'Todos'])
        ->merge(collect(\App\Models\Contacto::ESTADOS)->map(fn ($e) => $e[1]));
@endphp

@section('contenido')
    <div class="panel-titulo">
        <div>
            <h1>Mensajes</h1>
            <p>Lo que escriben desde la página de contacto.</p>
        </div>
    </div>

    <nav class="pestanas" aria-label="Filtrar por estado">
        @foreach ($pestanas as $clave => $nombre)
            <a href="{{ route('panel.mensajes', $clave === 'todos' ? [] : ['estado' => $clave]) }}"
               @if ($estado === $clave) aria-current="page" @endif>{{ $nombre }}@if ($clave === 'pendiente') ({{ $sinAtender }})@endif</a>
        @endforeach
    </nav>

    @if ($mensajes->isEmpty())
        <p class="lista-vacia texto-suave">{{ $vacios[$estado] }}</p>
    @else
        <ul class="lista-revision lista-mensajes">
            @foreach ($mensajes as $m)
                <li>
                    <span class="revision-datos">
                        <span class="mensaje-encabezado">
                            <a href="{{ route('panel.mensajes.ver', $m) }}" class="mensaje-asunto">{{ $m->asunto }}</a>
                            <span class="estado {{ $m->estado_clase }}">{{ $m->estado_texto }}</span>
                        </span>
                        <span class="mensaje-resumen">{{ \Illuminate\Support\Str::limit($m->mensaje, 160) }}</span>
                        <span class="revision-autor">{{ $m->nombre }} · {{ $m->correo }} · {{ $m->fecha_texto }}</span>
                    </span>

                    <div class="revision-acciones">
                        <a href="{{ route('panel.mensajes.ver', $m) }}" class="enlace">Abrir</a>
                        @if ($m->estado === 'pendiente')
                            <form method="POST" action="{{ route('panel.mensajes.atender', $m) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="boton-chico boton-chico-ok">Marcar como atendido</button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('panel.mensajes.eliminar', $m) }}"
                              onsubmit="return confirm(@js('¿Eliminar el mensaje «' . $m->asunto . '»? No se puede deshacer.'));">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="boton-texto enlace enlace-peligro">Eliminar</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>

        @include('partials.paginas', ['paginas' => $mensajes, 'anterior' => 'Página anterior', 'siguiente' => 'Página siguiente'])
    @endif
@endsection
