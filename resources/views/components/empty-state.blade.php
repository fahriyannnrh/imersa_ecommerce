@props(['title' => 'Produk Tidak Ditemukan', 'message' => 'Maaf, kami tidak dapat menemukan produk yang sesuai dengan pencarian atau filter Anda.', 'resetUrl' => route('products.index')])

<div class="empty-state">
    <div class="empty-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            <line x1="8" y1="11" x2="14" y2="11"></line>
        </svg>
    </div>
    <h3 class="empty-title">{{ $title }}</h3>
    <p class="empty-desc">{{ $message }}</p>
    @if ($resetUrl)
        <a href="{{ $resetUrl }}" class="btn btn-outline">
            Reset Pencarian & Filter
        </a>
    @endif
</div>
