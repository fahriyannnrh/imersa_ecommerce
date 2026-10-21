@props(['rating' => 0, 'showScore' => true, 'count' => null])

@php
    $rounded = round($rating, 1);
    $fullStars = floor($rounded);
    $hasHalf = ($rounded - $fullStars) >= 0.5;
@endphp

<div class="product-rating" title="Rating: {{ $rounded }} dari 5">
    <div style="display: flex; align-items: center; gap: 2px;">
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <= $fullStars)
                {{-- Full Star --}}
                <svg width="15" height="15" viewBox="0 0 20 20" fill="#F59E0B" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 1.5l2.588 5.244 5.787.841-4.188 4.082.989 5.763L10 14.711l-5.176 2.719.989-5.763-4.188-4.082 5.787-.841L10 1.5z"/>
                </svg>
            @elseif ($i == $fullStars + 1 && $hasHalf)
                {{-- Half Star --}}
                <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="halfStarGrad">
                            <stop offset="50%" stop-color="#F59E0B"/>
                            <stop offset="50%" stop-color="#E2E8F0"/>
                        </linearGradient>
                    </defs>
                    <path d="M10 1.5l2.588 5.244 5.787.841-4.188 4.082.989 5.763L10 14.711l-5.176 2.719.989-5.763-4.188-4.082 5.787-.841L10 1.5z" fill="url(#halfStarGrad)"/>
                </svg>
            @else
                {{-- Empty Star --}}
                <svg width="15" height="15" viewBox="0 0 20 20" fill="#CBD5E1" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 1.5l2.588 5.244 5.787.841-4.188 4.082.989 5.763L10 14.711l-5.176 2.719.989-5.763-4.188-4.082 5.787-.841L10 1.5z"/>
                </svg>
            @endif
        @endfor
    </div>
    @if ($showScore)
        <span style="margin-left: 4px; font-weight: 700; color: var(--slate-800);">{{ number_format($rating, 1) }}</span>
    @endif
    @if ($count !== null)
        <span style="font-weight: 500; color: var(--slate-400);">({{ $count }})</span>
    @endif
</div>
