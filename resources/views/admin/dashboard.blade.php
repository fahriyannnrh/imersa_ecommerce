@extends('layouts.admin')

@section('title', 'Dashboard Admin - PT Imersa')
@section('header_title', 'Ringkasan Dashboard')

@section('content')
    {{-- Metric Cards Grid --}}
    <div class="metrics-grid">
        <div class="metric-card">
            <div>
                <div class="metric-label">Total Produk Katalog</div>
                <div class="metric-value">{{ $totalProducts }}</div>
                <div style="font-size: 0.8rem; color: var(--emerald-500); font-weight: 600; margin-top: 4px;">
                    Aktif di katalog
                </div>
            </div>
            <div class="metric-icon-box">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
            </div>
        </div>

        <div class="metric-card">
            <div>
                <div class="metric-label">Kategori Pakaian</div>
                <div class="metric-value">{{ $totalCategories }}</div>
                <div style="font-size: 0.8rem; color: var(--primary); font-weight: 600; margin-top: 4px;">
                    Kaos, kemeja, celana, dll.
                </div>
            </div>
            <div class="metric-icon-box" style="background-color: #fef3c7; color: #d97706;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="8" y1="6" x2="21" y2="6"></line>
                    <line x1="8" y1="12" x2="21" y2="12"></line>
                    <line x1="8" y1="18" x2="21" y2="18"></line>
                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                </svg>
            </div>
        </div>

        <div class="metric-card">
            <div>
                <div class="metric-label">Total Ulasan Pembeli</div>
                <div class="metric-value">{{ $totalReviews }}</div>
                <div style="font-size: 0.8rem; color: var(--emerald-500); font-weight: 600; margin-top: 4px;">
                    Rata-rata rating: ★ {{ $averageRating }}
                </div>
            </div>
            <div class="metric-icon-box" style="background-color: #ecfdf5; color: var(--emerald-500);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
            </div>
        </div>
    </div>

    {{-- Quick Action Bar --}}
    <div style="display: flex; gap: 12px; margin-bottom: 32px; flex-wrap: wrap;">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="padding: 10px 18px; font-size: 0.875rem;">
            + Tambah Produk Baru
        </a>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-outline" style="padding: 10px 18px; font-size: 0.875rem;">
            + Tambah Kategori
        </a>
        <a href="{{ route('admin.reviews.create') }}" class="btn btn-outline" style="padding: 10px 18px; font-size: 0.875rem;">
            + Tambah Ulasan
        </a>
    </div>

    {{-- Recent Products Table --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Produk Terbaru Ditambahkan</h3>
                <p style="font-size: 0.85rem; color: var(--slate-500);">Daftar 5 produk katalog teranyar</p>
            </div>
            <a href="{{ route('admin.products.index') }}" style="font-size: 0.875rem; font-weight: 700; color: var(--primary);">
                Kelola Semua Produk &rarr;
            </a>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Foto</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Rating</th>
                        <th>Shopee URL</th>
                        <th style="width: 100px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentProducts as $product)
                        <tr>
                            <td>
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="table-thumb" onerror="handleImageError(this)">
                            </td>
                            <td>
                                <div style="font-weight: 700; color: var(--slate-900);">{{ $product->name }}</div>
                                <span style="font-size: 0.75rem; color: var(--slate-400);">/products/{{ $product->slug }}</span>
                            </td>
                            <td>
                                <span style="padding: 3px 8px; background-color: var(--slate-100); border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600;">
                                    {{ $product->category->name ?? '-' }}
                                </span>
                            </td>
                            <td style="font-weight: 700; color: var(--slate-900);">
                                {{ $product->formatted_price }}
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 4px; font-weight: 700; color: var(--amber-500);">
                                    ★ {{ number_format($product->rating, 1) }}
                                </div>
                            </td>
                            <td>
                                <a href="{{ $product->shopee_url }}" target="_blank" rel="noopener noreferrer" style="color: var(--shopee); font-size: 0.8rem; font-weight: 600; text-decoration: underline;">
                                    Buka Shopee ↗
                                </a>
                            </td>
                            <td style="text-align: right;">
                                <div class="table-actions" style="justify-content: flex-end;">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-action" title="Edit Produk">
                                        ✎
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--slate-400); padding: 32px;">
                                Belum ada data produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Reviews Table --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Ulasan Pembeli Terkini</h3>
                <p style="font-size: 0.85rem; color: var(--slate-500);">Ulasan terbaru yang masuk dari pelanggan</p>
            </div>
            <a href="{{ route('admin.reviews.index') }}" style="font-size: 0.875rem; font-weight: 700; color: var(--primary);">
                Kelola Semua Ulasan &rarr;
            </a>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Pembeli</th>
                        <th>Produk</th>
                        <th>Rating</th>
                        <th>Isi Ulasan</th>
                        <th style="width: 100px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentReviews as $review)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <img src="{{ $review->avatar_url ?? asset('images/avatar-placeholder.svg') }}" alt="{{ $review->customer_name }}" style="width: 32px; height: 32px; border-radius: var(--radius-full); object-fit: cover;" onerror="handleAvatarError(this)">
                                    <span style="font-weight: 700; color: var(--slate-900);">{{ $review->customer_name }}</span>
                                </div>
                            </td>
                            <td style="font-size: 0.85rem; color: var(--slate-700);">
                                {{ $review->product->name ?? '-' }}
                            </td>
                            <td style="color: var(--amber-500); font-weight: 700;">
                                ★ {{ $review->rating }}
                            </td>
                            <td style="font-size: 0.85rem; color: var(--slate-600); max-width: 300px;">
                                {{ Str::limit($review->review_text, 80) }}
                            </td>
                            <td style="text-align: right;">
                                <div class="table-actions" style="justify-content: flex-end;">
                                    <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn-action" title="Edit Ulasan">
                                        ✎
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--slate-400); padding: 32px;">
                                Belum ada data ulasan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
