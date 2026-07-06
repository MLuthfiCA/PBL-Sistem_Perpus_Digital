@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center space-x-2 sm:space-x-3 mt-8">
        
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-white/40 text-gray-400 cursor-not-allowed shadow-sm border border-white/50 backdrop-blur-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-white/80 text-burgundy-500 hover:bg-burgundy-50 hover:text-burgundy-600 shadow-md shadow-red-50 border border-white transition-all duration-300 hover:scale-105 backdrop-blur-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="hidden sm:flex items-center space-x-2">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="flex items-center justify-center w-10 h-10 text-gray-500 font-medium">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-burgundy-500 text-white font-bold shadow-lg shadow-red-200 border border-burgundy-400 scale-110">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-white/60 text-gray-600 font-semibold hover:bg-white hover:text-burgundy-500 hover:shadow-md border border-white/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-0.5">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
        
        {{-- Mobile Current Page Indicator --}}
        <div class="flex sm:hidden items-center justify-center px-4 h-10 rounded-xl bg-white/60 text-gray-600 font-semibold border border-white/50 backdrop-blur-md">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-white/80 text-burgundy-500 hover:bg-burgundy-50 hover:text-burgundy-600 shadow-md shadow-red-50 border border-white transition-all duration-300 hover:scale-105 backdrop-blur-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        @else
            <span class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-white/40 text-gray-400 cursor-not-allowed shadow-sm border border-white/50 backdrop-blur-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </span>
        @endif
    </nav>
@endif
