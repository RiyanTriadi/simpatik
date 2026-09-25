@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between mt-4">
        <div class="hidden sm:block">
            <p class="text-sm text-gray-700 leading-5">
                Menampilkan <span class="font-medium">{{ $paginator->firstItem() }}</span>
                sampai <span class="font-medium">{{ $paginator->lastItem() }}</span>
                dari <span class="font-medium">{{ $paginator->total() }}</span> hasil
            </p>
        </div>

        <div class="flex-1 flex justify-end sm:flex-none">
            <span class="relative z-0 inline-flex">

                @if ($paginator->onFirstPage())
                    <span
                        class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-not-allowed">
                        <i class="ri-arrow-left-s-line"></i>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                        <i class="ri-arrow-left-s-line"></i>
                    </a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span
                            class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span
                                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-emerald-500 border border-emerald-500 cursor-default leading-5">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                        class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                        <i class="ri-arrow-right-s-line"></i>
                    </a>
                @else
                    <span
                        class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-not-allowed">
                        <i class="ri-arrow-right-s-line"></i>
                    </span>
                @endif

            </span>
        </div>
    </nav>
@endif