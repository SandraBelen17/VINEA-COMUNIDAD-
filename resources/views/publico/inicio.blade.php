@extends('layouts.publico')

@section('titulo', 'Inicio')

@section('contenido')
    <section class="slider" aria-label="Fotos de la parroquia" data-slider>
        <div class="slider-foto activa">
            <img src="{{ asset('img/altar.jpg') }}" alt="Altar del templo parroquial con la imagen de María Madre de Dios" style="object-position: center 40%">
        </div>
        <div class="slider-foto">
            <img src="{{ asset('img/interior.jpg') }}" alt="Interior del templo parroquial" style="object-position: center 55%">
        </div>
        <div class="slider-foto">
            <img src="{{ asset('img/fachada.jpg') }}" alt="Fachada de la parroquia y el atrio" style="object-position: center 55%">
        </div>
        <div class="slider-velo" aria-hidden="true"></div>

        <div class="slider-texto">
            <span class="etiqueta">Diócesis de Tehuacán, Puebla</span>
            <h1>Parroquia María Madre de Dios</h1>

            <div class="slider-controles">
                <div class="slider-puntos">
                    @for ($i = 1; $i <= 3; $i++)
                        <button type="button" class="slider-punto" aria-label="Ver foto {{ $i }}"><span></span></button>
                    @endfor
                </div>
                <div class="slider-flechas">
                    <button type="button" class="slider-flecha" aria-label="Foto anterior" data-anterior><i data-lucide="chevron-left"></i></button>
                    <button type="button" class="slider-flecha" aria-label="Foto siguiente" data-siguiente><i data-lucide="chevron-right"></i></button>
                </div>
            </div>
        </div>
    </section>

    {{-- 1. Parroquia (texto fijo) --}}
    <section class="seccion" id="parroquia" aria-labelledby="titulo-parroquia">
        <div class="contenedor seccion-columnas seccion-columnas-anchas">
            <div class="inicio-bloque">
                <span class="etiqueta">Nuestra parroquia</span>
                <h2 id="titulo-parroquia">Una comunidad mariana en Tehuacán</h2>
                <div class="inicio-foto">
                    <img src="{{ asset('img/fachada.jpg') }}" alt="Fachada de la parroquia María Madre de Dios" loading="lazy">
                </div>
            </div>
            <div class="inicio-bloque parroquia-texto">
                <p class="parroquia-descripcion">
                    Somos una parroquia mariana de la Diócesis de Tehuacán, Puebla, dedicada a María Madre de Dios.
                    Además del templo parroquial, la comunidad se reúne en ocho capillas de sus sectores para
                    celebrar la misa, los sacramentos y la vida de fe.
                </p>
                <div class="parroco">
                    <span class="parroco-etiqueta">Párroco</span>
                    <span class="parroco-nombre">Pbro. Juan José Herrera Martínez</span>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Horarios: las mismas fichas que /horarios (partials/semana). Sin JavaScript se ven
         las tres semanas una debajo de otra; con JavaScript (vinea.js) se vuelven pestañas. --}}
    <section class="seccion seccion-suave" aria-labelledby="titulo-horarios">
        <div class="contenedor inicio-bloque" data-pestanas>
            <div class="inicio-horarios-encabezado">
                <div class="seccion-encabezado">
                    <span class="etiqueta">Horarios</span>
                    <h2 id="titulo-horarios">Esta semana en la parroquia</h2>
                </div>
                <div class="pestanas" data-pestanas-botones hidden>
                    @foreach (\App\Models\Horario::TIPOS as $tipo => [$singular, $plural])
                        <button type="button" aria-pressed="{{ $loop->first ? 'true' : 'false' }}" aria-controls="semana-{{ $tipo }}">{{ $plural }}</button>
                    @endforeach
                </div>
            </div>

            @foreach (\App\Models\Horario::TIPOS as $tipo => [$singular, $plural])
                <div class="horario-panel" id="semana-{{ $tipo }}" data-pestana-panel>
                    <h3 class="horario-panel-titulo">{{ $plural }}</h3>
                    @include('partials.semana', ['tipo' => $tipo, 'horas' => $semana[$tipo], 'hoy' => $hoy])
                </div>
            @endforeach

            <a href="{{ route('horarios') }}" class="enlace">Ver todos los horarios</a>
        </div>
    </section>

    {{-- 3. Capillas y sectores (texto fijo: config/parroquia.php) --}}
    <section class="seccion" id="capillas" aria-labelledby="titulo-capillas">
        <div class="contenedor seccion-columnas">
            <div class="seccion-encabezado">
                <span class="etiqueta">Capillas y sectores</span>
                <h2 id="titulo-capillas">Ocho capillas, una sola comunidad</h2>
                <p class="texto-suave">En las capillas la misa es los domingos, salvo las excepciones indicadas.</p>
            </div>

            <ul class="lista-capillas">
                @foreach ($capillas as $c)
                    <li>
                        <span class="capilla-datos">
                            <span class="capilla-nombre">{{ $c['nombre'] }}</span>
                            <span class="capilla-zona">{{ $c['zona'] ?? '[Zona por confirmar]' }}</span>
                        </span>
                        <span class="capilla-cuando">{{ $c['dia'] }}@if ($c['hora']) · {{ $c['hora'] }}@endif</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- 4. Sacramentos: desplegables con <details> (funcionan sin JavaScript) --}}
    <section class="seccion seccion-suave" id="sacramentos" aria-labelledby="titulo-sacramentos">
        <div class="contenedor seccion-columnas">
            <div class="seccion-encabezado">
                <span class="etiqueta">Sacramentos</span>
                <h2 id="titulo-sacramentos">Requisitos</h2>
                <p class="texto-suave">Consulta lo que necesitas antes de acudir a la oficina parroquial.</p>
                <p class="texto-suave">¿Tienes dudas? <a href="{{ route('mis-citas') }}" class="enlace">Pedir una cita</a></p>
            </div>

            <div class="lista-sacramentos">
                @foreach ($sacramentos as $nombre => $requisitos)
                    <details class="sacramento">
                        <summary>
                            <span class="sacramento-nombre">{{ $nombre }}</span>
                            <span class="sacramento-signo" aria-hidden="true"></span>
                        </summary>
                        <ul>
                            @foreach ($requisitos as $requisito)
                                <li>{{ $requisito }}</li>
                            @endforeach
                        </ul>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 5 y 6. Próximos eventos y grupos, lado a lado --}}
    <section class="seccion">
        <div class="contenedor seccion-columnas seccion-columnas-anchas">
            <div class="inicio-bloque" aria-labelledby="titulo-eventos" role="region">
                <div class="seccion-encabezado">
                    <span class="etiqueta">Calendario</span>
                    <h2 id="titulo-eventos">Próximos eventos</h2>
                </div>

                @if ($eventos->isEmpty())
                    <p class="lista-vacia texto-suave">Por ahora no hay eventos próximos. Vuelve pronto o consulta el calendario.</p>
                @else
                    <ul class="lista-eventos lista-eventos-compacta">
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
                                    @if ($e->detalle_texto)
                                        <span class="evento-detalle texto-suave">{{ $e->detalle_texto }}</span>
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <a href="{{ route('calendario') }}" class="enlace">Ver calendario</a>
            </div>

            <div class="inicio-bloque" aria-labelledby="titulo-grupos" role="region">
                <div class="seccion-encabezado">
                    <span class="etiqueta">Comunidad</span>
                    <h2 id="titulo-grupos">Grupos parroquiales</h2>
                </div>

                @if ($grupos->isEmpty())
                    <p class="lista-vacia texto-suave">Pronto publicaremos los grupos de la parroquia.</p>
                @else
                    <ul class="lista-simple">
                        @foreach ($grupos as $g)
                            <li>
                                <span class="lista-simple-titulo">{{ $g->nombre }}</span>
                                @if ($g->detalle_texto)
                                    <span class="texto-suave">{{ $g->detalle_texto }}</span>
                                @endif
                                @if ($g->coordinador)
                                    <span class="texto-suave">Coordina: {{ $g->coordinador }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif

                <a href="{{ route('grupos') }}" class="enlace">Conoce los grupos</a>
            </div>
        </div>
    </section>

    {{-- 7. Invitación al muro de oraciones --}}
    <section class="franja-muro" aria-labelledby="titulo-muro">
        <img src="{{ asset('img/interior.jpg') }}" alt="" loading="lazy">
        <div class="slider-velo" aria-hidden="true"></div>
        <div class="contenedor franja-contenido">
            <div class="seccion-encabezado">
                <span class="etiqueta">Muro de oraciones</span>
                <h2 id="titulo-muro">Deja tu intención o enciende una velita</h2>
            </div>
            <a href="{{ route('muro') }}" class="boton boton-claro"><i data-lucide="flame"></i> Ir al muro</a>
        </div>
    </section>
@endsection
