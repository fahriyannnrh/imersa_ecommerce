@props(['product'])

<div class="product-card">
    <div class="product-card-img-wrap">
        <a href="{{ route('products.show', $product->slug) }}">
            <img 
                src="{{ $product->image_url }}" 
                alt="{{ $product->name }}" 
                loading="lazy" 
                onerror="handleImageError(this)"
            >
        </a>
        @if ($product->category)
            <span class="product-badge-cat">{{ $product->category->name }}</span>
        @endif
    </div>
    <div class="product-card-body">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
            <x-rating-stars :rating="$product->rating" :showScore="true" />
            @if ($product->is_featured)
                <span style="font-size: 0.7rem; font-weight: 700; color: var(--primary); background-color: var(--primary-light); padding: 2px 8px; border-radius: var(--radius-full);">Pilihan</span>
            @endif
        </div>
        <h3 class="product-title">
            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
        </h3>
        <div class="product-card-footer">
            <div class="product-price">{{ $product->formatted_price }}</div>
            <a href="{{ route('products.show', $product->slug) }}" class="product-card-btn" title="Lihat Detail Produk">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>
    </div>
</div>
