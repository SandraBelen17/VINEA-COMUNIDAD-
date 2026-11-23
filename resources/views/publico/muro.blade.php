@extends('layouts.publico')

@section('titulo', 'Muro de oraciones')

@section('contenido')
    <section class="portada">
        <img src="{{ asset('img/interior.jpg') }}" alt="" style="object-position: center 45%">
        <div class="slider-velo" aria-hidden="true"></div>
        <div class="portada-texto">
            <span class="etiqueta">Muro de oraciones</span>
            <h1>Unidos en oración</h1>
            <p>Comparte tu intención o enciende una velita. La comunidad reza contigo.</p>
        </div>
    </section>

    <section class="seccion-muro">
        <div class="contenedor muro-columnas">
            <div class="muro-formulario" id="deja-tu-intencion">
                <h2>Deja tu intención</h2>

                @include('partials.avisos')

                <form method="POST" action="{{ route('muro.guardar') }}" class="formulario" novalidate>
                    @csrf

                    <fieldset class="opciones-tipo">
                        <legend>¿Qué quieres dejar?</legend>
                        <div class="opciones">
                            @foreach (\App\Models\Intencion::TIPOS as $clave => [$texto, $icono])
                                <label class="opcion">
                                    <input type="radio" name="tipo_intencion" value="{{ $clave }}" @checked(old('tipo_intencion', 'oracion') === $clave)>
                                    <i data-lucide="{{ $icono }}"></i> {{ $texto }}
                                </label>
                            @endforeach
                        </div>
                        @error('tipo_intencion') <span class="error-campo">{{ $message }}</span> @enderror
                    </fieldset>

                    <div class="campo">
                        <label for="contenido">Tu intención</label>
                        <textarea id="contenido" name="contenido" rows="4" maxlength="500" required
                                  placeholder="Por la salud de mi mamá, Rosa">{{ old('contenido') }}</textarea>
                        @error('contenido') <span class="error-campo">{{ $message }}</span> @enderror
                    </div>

                    <p class="texto-suave muro-nota">Antes de aparecer en el muro, tu intención la revisa el equipo de la parroquia.</p>

                    <div>
                        <button type="submit" class="boton">Enviar intención</button>
                    </div>
                </form>
            </div>

            <div class="muro-comunidad" id="comunidad">
                <h2>Intenciones de la comunidad</h2>

                @if ($intenciones->isEmpty())
                    <p class="muro-vacio texto-suave">Todavía no hay intenciones publicadas.</p>
                @else
                    <ul class="lista-intenciones">
                        @foreach ($intenciones as $i)
                            <li>
                                <span class="intencion-encabezado">
                                    <span class="intencion-tipo"><i data-lucide="{{ $i->tipo_icono }}"></i> {{ $i->tipo_texto }}</span>
                                    <span class="intencion-fecha">{{ $i->fecha_texto }}</span>
                                </span>
                                <p class="intencion-texto">{{ $i->contenido }}</p>
                            </li>
                        @endforeach
                    </ul>

                    @include('partials.paginas', ['paginas' => $intenciones])
                @endif
            </div>
        </div>
    </section>
@endsection
