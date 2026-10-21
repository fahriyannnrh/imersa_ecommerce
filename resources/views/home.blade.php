@extends('layouts.app')

@section('title', 'PT Imersa - Official Clothing Catalog & Apparel')

@section('content')
    {{-- Hero Section --}}
    <section class="hero-section">
        <div class="container">
            <div class="hero-grid">
                <div>
                    <span class="hero-badge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        NEW COLLECTION 2026
                    </span>
                    <h1 class="hero-title">Temukan Koleksi Terbaik Kami</h1>
                    <p class="hero-desc">
                        Koleksi pakaian kasual & streetwear berkualitas tinggi dari PT Imersa. Dibuat dengan material katun pilihan yang dirancang untuk kenyamanan maksimal dan ketahanan gaya sehari-hari.
                    </p>
                    <div class="hero-actions">
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <span>Jelajahi Produk</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                        <a href="https://shopee.co.id" target="_blank" rel="noopener noreferrer" class="btn btn-outline" style="gap: 10px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="var(--shopee)">
                                <path d="M19.38 6.64H16.5a4.5 4.5 0 0 0-9 0H4.62A1.63 1.63 0 0 0 3 8.27l1.37 12.35A2.45 2.45 0 0 0 6.82 23h10.36a2.45 2.45 0 0 0 2.45-2.38L21 8.27a1.63 1.63 0 0 0-1.62-1.63zM12 3.75a2.88 2.88 0 0 1 2.87 2.89H9.13A2.88 2.88 0 0 1 12 3.75zm6 16.75a.82.82 0 0 1-.82.78H6.82a.82.82 0 0 1-.82-.78L4.69 8.27H19.3z"/>
                            </svg>
                            <span>Shopee Official</span>
                        </a>
                    </div>
                </div>

                <div>
                    <div class="hero-banner-card">
                        <img 
                            src="{{ asset('images/hero-banner.jpg') }}" 
                            alt="PT Imersa Fashion Showcase"
                            onerror="handleImageError(this)"
                        >
                        <div class="hero-banner-tag">
                            <div>
                                <div style="font-weight: 700; font-size: 0.95rem;">Urban Daily Wear</div>
                                <div style="font-size: 0.8rem; color: var(--slate-300);">Authentic Craftsmanship</div>
                            </div>
                            <div style="background-color: var(--primary); padding: 6px 14px; border-radius: var(--radius-full); font-size: 0.8rem; font-weight: 700;">
                                100% Original
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Kategori Section --}}
    <section class="categories-section">
        <div class="container">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Kategori Pilihan</h2>
                    <p class="section-subtitle">Pilih kategori pakaian yang sesuai dengan preferensi gayamu</p>
                </div>
                <a href="{{ route('products.index') }}" style="font-size: 0.9rem; font-weight: 700; color: var(--primary);">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="category-grid">
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="category-card">
                        <div class="category-icon-box">
                            @if ($category->slug === 'kaos')
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.38 3.46L16 2a4 4 0 01-8 0L3.62 3.46a2 2 0 00-1.34 2.23l.58 3.47a1 1 0 00.99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 002-2V10h2.15a1 1 0 00.99-.84l.58-3.47a2 2 0 00-1.34-2.23z"/>
                                </svg>
                            @elseif ($category->slug === 'kemeja')
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                            @elseif ($category->slug === 'celana')
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 2h16l-2 20h-4l-2-12-2 12H6L4 2z"/>
                                </svg>
                            @elseif ($category->slug === 'jaket-hoodie')
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2a7 7 0 0 0-7 7v1h2v12h10V10h2V9a7 7 0 0 0-7-7z"/>
                                    <line x1="12" y1="10" x2="12" y2="22"/>
                                </svg>
                            @else
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9"/>
                                    <polyline points="12 7 12 12 15 15"/>
                                </svg>
                            @endif
                        </div>
                        <h3 class="category-name">{{ $category->name }}</h3>
                        <span class="category-count">{{ $category->products_count }} Produk</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Featured Products Section --}}
    <section class="products-section">
        <div class="container">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Produk Pilihan & Populer</h2>
                    <p class="section-subtitle">Koleksi pakaian paling diminati dengan ulasan terbaik</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-outline" style="padding: 8px 18px; font-size: 0.875rem;">
                    Buka Semua Katalog &rarr;
                </a>
            </div>

            <div class="product-grid">
                @forelse ($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div style="grid-column: 1 / -1;">
                        <x-empty-state />
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Trust / Value Proposition Banner --}}
    <section style="background-color: var(--slate-50); padding: 48px 0; border-top: 1px solid var(--slate-200); border-bottom: 1px solid var(--slate-200);">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 32px;">
                <div style="display: flex; gap: 16px; align-items: flex-start;">
                    <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background-color: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 style="font-weight: 700; color: var(--slate-900); margin-bottom: 4px;">100% Produk Original</h4>
                        <p style="font-size: 0.875rem; color: var(--slate-500); line-height: 1.5;">Seluruh produk dirancang dan diproduksi mandiri oleh tim PT Imersa dengan kontrol mutu ketat.</p>
                    </div>
                </div>

                <div style="display: flex; gap: 16px; align-items: flex-start;">
                    <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background-color: #fff1f2; color: var(--shopee); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                    </div>
                    <div>
                        <h4 style="font-weight: 700; color: var(--slate-900); margin-bottom: 4px;">Order Mudah di Shopee</h4>
                        <p style="font-size: 0.875rem; color: var(--slate-500); line-height: 1.5;">Transaksi aman terverifikasi via Shopee Mall dengan promo gratis ongkir dan proteksi pembeli.</p>
                    </div>
                </div>

                <div style="display: flex; gap: 16px; align-items: flex-start;">
                    <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background-color: #ecfdf5; color: var(--emerald-500); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <div>
                        <h4 style="font-weight: 700; color: var(--slate-900); margin-bottom: 4px;">Ulasan Terverifikasi</h4>
                        <p style="font-size: 0.875rem; color: var(--slate-500); line-height: 1.5;">Ratusan ulasan asli dari pembeli dengan rating bintang tinggi dan foto produk riil.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tentang PT Imersa Section --}}
    <section id="tentang" style="padding: 72px 0;">
        <div class="container">
            <div style="max-width: 760px; margin: 0 auto; text-align: center;">
                <span class="hero-badge">TENTANG PT IMERSA</span>
                <h2 style="font-size: 2.25rem; font-weight: 800; color: var(--slate-900); margin-bottom: 16px; letter-spacing: -0.02em;">
                    Kualitas Terbaik Untuk Gaya Sehari-hari
                </h2>
                <p style="font-size: 1.05rem; color: var(--slate-600); line-height: 1.8; margin-bottom: 24px;">
                    PT Imersa adalah perusahaan konveksi dan produsen pakaian modern yang berdedikasi menciptakan pakaian siap pakai bernilai estetika tinggi, kenyamanan terbaik, dan harga yang terjangkau bagi masyarakat Indonesia.
                </p>
                <p style="font-size: 0.95rem; color: var(--slate-500); line-height: 1.7;">
                    Dengan fokus pada pemilihan material ramah kulit serta presisi potongan pola, setiap produk kami disiapkan untuk menemani rutinitas harian Anda dengan penuh rasa percaya diri.
                </p>
            </div>
        </div>
    </section>
@endsection
