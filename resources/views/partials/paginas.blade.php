{{-- Enlaces anterior / siguiente para listas con simplePaginate().
     Por defecto dicen "Más recientes / Más antiguas" (listas de la más nueva a la más vieja);
     se pueden cambiar con 'anterior' y 'siguiente' en el @include. --}}
@if ($paginas->hasPages())
    <nav class="paginas" aria-label="Páginas">
        @if ($paginas->previousPageUrl())
            <a href="{{ $paginas->previousPageUrl() }}" class="enlace" rel="prev">{{ $anterior ?? 'Más recientes' }}</a>
        @endif
        @if ($paginas->nextPageUrl())
            <a href="{{ $paginas->nextPageUrl() }}" class="enlace" rel="next">{{ $siguiente ?? 'Más antiguas' }}</a>
        @endif
    </nav>
@endif
