@extends('layouts.panel')

@section('titulo', 'Mensaje')

@section('contenido')
    <a href="{{ route('panel.mensajes') }}" class="enlace volver"><i data-lucide="arrow-left"></i> Volver a mensajes</a>

    <div class="panel-titulo">
        <div>
            <div class="mensaje-encabezado">
                <h1>{{ $m->asunto }}</h1>
                <span class="estado {{ $m->estado_clase }}">{{ $m->estado_texto }}</span>
            </div>
        </div>
    </div>

    <dl class="datos-mensaje">
        <div>
            <dt>Nombre</dt>
            <dd>{{ $m->nombre }}</dd>
        </div>
        <div>
            <dt>Correo</dt>
            <dd><a href="mailto:{{ $m->correo }}">{{ $m->correo }}</a></dd>
        </div>
        <div>
            <dt>Teléfono</dt>
            <dd>
                @if ($m->telefono)
                    <a href="tel:{{ preg_replace('/\D/', '', $m->telefono) }}">{{ \App\Models\Contacto::formatoTelefono($m->telefono) }}</a>
                @else
                    —
                @endif
            </dd>
        </div>
        <div>
            <dt>Enviado</dt>
            <dd>{{ $m->fecha_texto }}</dd>
        </div>
    </dl>

    <p class="mensaje-completo">{{ $m->mensaje }}</p>

    <div class="botones">
        <a href="{{ $m->enlace_responder }}" class="boton">Responder por correo</a>
        @if ($m->estado === 'pendiente')
            <form method="POST" action="{{ route('panel.mensajes.atender', $m) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="boton boton-linea">Marcar como atendido</button>
            </form>
        @endif
        <form method="POST" action="{{ route('panel.mensajes.eliminar', $m) }}"
              onsubmit="return confirm(@js('¿Eliminar el mensaje «' . $m->asunto . '»? No se puede deshacer.'));">
            @csrf
            @method('DELETE')
            <button type="submit" class="boton-texto enlace enlace-peligro">Eliminar</button>
        </form>
    </div>
@endsection
