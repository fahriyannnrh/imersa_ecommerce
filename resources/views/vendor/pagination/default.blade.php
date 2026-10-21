@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="custom-pagination">
        {{-- Summary Info --}}
        <div class="pagination-info">
            Menampilkan <span class="font-semibold">{{ $paginator->firstItem() }}</span> - <span class="font-semibold">{{ $paginator->lastItem() }}</span> dari <span class="font-semibold">{{ $paginator->total() }}</span> hasil
        </div>

        {{-- Controls --}}
        <div class="pagination-buttons">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-btn disabled" aria-disabled="true" aria-label="Halaman Sebelumnya">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                    <span class="btn-text">Sebelumnya</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn" rel="prev" aria-label="Halaman Sebelumnya">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                    <span class="btn-text">Sebelumnya</span>
                </a>
            @endif

            {{-- Numbered Page Links --}}
            <div class="pagination-numbers">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="pagination-number disabled" aria-disabled="true">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-number active" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn" rel="next" aria-label="Halaman Selanjutnya">
                    <span class="btn-text">Selanjutnya</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
            @else
                <span class="pagination-btn disabled" aria-disabled="true" aria-label="Halaman Selanjutnya">
                    <span class="btn-text">Selanjutnya</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
