{{-- Enlaces "más recientes / más antiguas" para listas con simplePaginate() (de la más nueva a la más vieja). --}}
@if ($paginas->hasPages())
    <nav class="paginas" aria-label="Páginas">
        @if ($paginas->previousPageUrl())
            <a href="{{ $paginas->previousPageUrl() }}" class="enlace" rel="prev">Más recientes</a>
        @endif
        @if ($paginas->nextPageUrl())
            <a href="{{ $paginas->nextPageUrl() }}" class="enlace" rel="next">Más antiguas</a>
        @endif
    </nav>
@endif
