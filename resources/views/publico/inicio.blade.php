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

    {{-- Las demás secciones de Inicio (parroquia, horarios, capillas, sacramentos,
         eventos, grupos y muro) se agregan en el módulo Inicio. --}}
    <section class="seccion" id="parroquia">
        <div class="contenedor">
            <div class="seccion-encabezado">
                <span class="etiqueta">Nuestra parroquia</span>
                <h2>Una comunidad mariana en Tehuacán</h2>
                <p class="texto-suave">Párroco: Pbro. Juan José Herrera Martínez. El resto de esta página se construye en el módulo Inicio.</p>
            </div>
        </div>
    </section>
@endsection
