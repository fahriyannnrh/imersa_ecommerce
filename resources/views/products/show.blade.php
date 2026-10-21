@extends('layouts.app')

@section('title', $product->name . ' - PT Imersa')
@section('meta_description', Str::limit(strip_tags($product->description), 150))

@section('content')
    <div class="container">
        {{-- Breadcrumbs --}}
        <div class="breadcrumb-wrap">
            <ul class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-separator">/</li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Katalog</a></li>
                @if ($product->category)
                    <li class="breadcrumb-separator">/</li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}">
                            {{ $product->category->name }}
                        </a>
                    </li>
                @endif
                <li class="breadcrumb-separator">/</li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ul>
        </div>

        {{-- Product Detail Main Grid --}}
        <div class="detail-layout">
            {{-- Left: Product Visual Showcase --}}
            <div class="detail-gallery">
                <div class="detail-main-img">
                    <img 
                        src="{{ $product->image_url }}" 
                        alt="{{ $product->name }}" 
                        id="mainProductImage"
                        onerror="handleImageError(this)"
                    >
                </div>

                <div style="display: flex; gap: 12px;">
                    <div style="width: 72px; height: 72px; border-radius: var(--radius-md); border: 2px solid var(--primary); overflow: hidden; cursor: pointer;">
                        <img src="{{ $product->image_url }}" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover;" onerror="handleImageError(this)">
                    </div>
                </div>
            </div>

            {{-- Right: Product Details & Order CTA --}}
            <div class="detail-info">
                @if ($product->category)
                    <span class="detail-cat-badge">{{ $product->category->name }}</span>
                @endif

                <h1 class="detail-title">{{ $product->name }}</h1>

                <div class="detail-rating-wrap">
                    <x-rating-stars :rating="$product->rating" :showScore="true" />
                    <span style="color: var(--slate-300);">•</span>
                    <span style="font-weight: 600; color: var(--slate-600);">{{ $product->reviews->count() }} Ulasan Pembeli</span>
                    @if ($product->is_featured)
                        <span style="color: var(--slate-300);">•</span>
                        <span style="background-color: var(--primary-light); color: var(--primary); font-size: 0.75rem; font-weight: 700; padding: 3px 10px; border-radius: var(--radius-full);">Produk Unggulan</span>
                    @endif
                </div>

                <div class="detail-price">
                    {{ $product->formatted_price }}
                </div>

                <div class="detail-divider"></div>

                <div class="order-box">
                    <div style="margin-bottom: 16px;">
                        <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--slate-700); margin-bottom: 4px;">Pemesanan Resmi:</h4>
                        <p style="font-size: 0.85rem; color: var(--slate-500);">
                            Untuk menjamin keaslian produk, kepastian stok, gratis ongkir, dan garansi pengiriman aman, seluruh transaksi dilakukan secara resmi melalui Shopee Official PT Imersa.
                        </p>
                    </div>

                    {{-- Shopee Order Direct Button --}}
                    <a 
                        href="{{ $product->shopee_url }}" 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        class="btn btn-shopee-lg"
                        title="Beli {{ $product->name }} di Shopee"
                    >
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19.38 6.64H16.5a4.5 4.5 0 0 0-9 0H4.62A1.63 1.63 0 0 0 3 8.27l1.37 12.35A2.45 2.45 0 0 0 6.82 23h10.36a2.45 2.45 0 0 0 2.45-2.38L21 8.27a1.63 1.63 0 0 0-1.62-1.63zM12 3.75a2.88 2.88 0 0 1 2.87 2.89H9.13A2.88 2.88 0 0 1 12 3.75zm6 16.75a.82.82 0 0 1-.82.78H6.82a.82.82 0 0 1-.82-.78L4.69 8.27H19.3z"/>
                        </svg>
                        <span>Order di Shopee</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px;">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                    </a>

                    <div class="order-notice">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        <span>Anda akan diarahkan ke halaman produk resmi PT Imersa di aplikasi/situs Shopee.</span>
                    </div>
                </div>

                {{-- Trust Features --}}
                <div class="trust-features">
                    <div class="trust-item">
                        <span class="trust-icon">✓</span>
                        <span>100% Produk Original</span>
                    </div>
                    <div class="trust-item">
                        <span class="trust-icon">✓</span>
                        <span>Pengiriman Cepat</span>
                    </div>
                    <div class="trust-item">
                        <span class="trust-icon">✓</span>
                        <span>Garansi Shopee</span>
                    </div>
                </div>

                <div class="detail-divider"></div>

                {{-- Product Description --}}
                <div>
                    <h3 class="detail-desc-title">Deskripsi Produk</h3>
                    <div class="detail-description">
                        {{ $product->description }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Reviews Section --}}
        <section class="reviews-section" id="ulasan">
            <div class="reviews-header">
                <div>
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--slate-900);">
                        Ulasan Pembeli ({{ $product->reviews->count() }})
                    </h2>
                    <p style="font-size: 0.9rem; color: var(--slate-500); margin-top: 4px;">
                        Penilaian asli dari pelanggan yang telah memesan produk ini
                    </p>
                </div>
                <div style="display: flex; align-items: center; gap: 8px; background-color: var(--slate-50); padding: 10px 18px; border-radius: var(--radius-md); border: 1px solid var(--slate-200);">
                    <span style="font-size: 1.5rem; font-weight: 800; color: var(--slate-900);">{{ number_format($product->rating, 1) }}</span>
                    <div>
                        <x-rating-stars :rating="$product->rating" :showScore="false" />
                        <span style="font-size: 0.75rem; color: var(--slate-400);">Skala 5.0</span>
                    </div>
                </div>
            </div>

            <div class="reviews-list">
                @forelse ($product->reviews as $review)
                    <div class="review-card">
                        <div class="review-card-header">
                            <div class="reviewer-meta">
                                <img 
                                    src="{{ $review->avatar_url ?? asset('images/avatar-placeholder.svg') }}" 
                                    alt="{{ $review->customer_name }}" 
                                    class="reviewer-avatar"
                                    onerror="handleAvatarError(this)"
                                >
                                <div>
                                    <div class="reviewer-name">{{ $review->customer_name }}</div>
                                    <div class="review-date">
                                        {{ $review->created_at ? $review->created_at->translatedFormat('d F Y') : 'Terverifikasi' }}
                                    </div>
                                </div>
                            </div>
                            <x-rating-stars :rating="$review->rating" :showScore="true" />
                        </div>

                        <p class="review-text">{{ $review->review_text }}</p>

                        @if ($review->photo_url)
                            <div style="margin-top: 12px;">
                                <img 
                                    src="{{ $review->photo_url }}" 
                                    alt="Foto Ulasan {{ $review->customer_name }}" 
                                    class="review-photo-thumb"
                                    title="Klik untuk memperbesar"
                                    onerror="this.style.display='none'"
                                >
                            </div>
                        @endif
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px; background-color: var(--slate-50); border-radius: var(--radius-lg); border: 1px dashed var(--slate-300);">
                        <p style="color: var(--slate-500); font-size: 0.95rem;">Belum ada ulasan untuk produk ini.</p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Related Products --}}
        @if ($relatedProducts->count() > 0)
            <section style="padding: 48px 0 80px; border-top: 1px solid var(--slate-200);">
                <div class="section-header">
                    <div>
                        <h2 class="section-title">Produk Terkait Lainnya</h2>
                        <p class="section-subtitle">Koleksi sejenis dari kategori {{ $product->category->name }}</p>
                    </div>
                    <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" style="font-size: 0.9rem; font-weight: 700; color: var(--primary);">
                        Lihat Kategori &rarr;
                    </a>
                </div>
                <div class="product-grid">
                    @foreach ($relatedProducts as $relProduct)
                        <x-product-card :product="$relProduct" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
