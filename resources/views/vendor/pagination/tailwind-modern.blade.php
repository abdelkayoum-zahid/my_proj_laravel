@if ($paginator->hasPages())
    <nav class="flex items-center justify-center gap-1" aria-label="Pagination Navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gray-200 text-gray-400 cursor-not-allowed shadow">&laquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gradient-to-r from-indigo-500 to-blue-500 text-white hover:from-indigo-600 hover:to-blue-600 transition shadow">&laquo;</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 text-gray-400 font-bold">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gradient-to-r from-pink-500 to-indigo-500 text-white font-bold shadow-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white text-indigo-600 hover:bg-indigo-50 hover:text-pink-600 font-semibold border border-indigo-200 shadow transition">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gradient-to-r from-indigo-500 to-pink-500 text-white hover:from-indigo-600 hover:to-pink-600 transition shadow">&raquo;</a>
        @else
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gray-200 text-gray-400 cursor-not-allowed shadow">&raquo;</span>
        @endif
    </nav>
@endif
