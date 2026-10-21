@extends('layouts.app')

@section('title', 'Katalog Pakaian & Produk - PT Imersa')

@section('content')
    <div class="container">
        {{-- Breadcrumbs --}}
        <div class="breadcrumb-wrap">
            <ul class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-separator">/</li>
                <li class="breadcrumb-item {{ !$selectedCategory ? 'active' : '' }}">
                    @if ($selectedCategory)
                        <a href="{{ route('products.index') }}">Katalog Produk</a>
                    @else
                        Katalog Produk
                    @endif
                </li>
                @if ($selectedCategory)
                    <li class="breadcrumb-separator">/</li>
                    <li class="breadcrumb-item active">{{ $selectedCategory->name }}</li>
                @endif
            </ul>
        </div>

        <div class="catalog-layout">
            {{-- Left Sidebar: Filters --}}
            <aside class="catalog-sidebar">
                <h3 class="sidebar-title">Filter Produk</h3>

                {{-- Search Form --}}
                <div class="filter-group">
                    <label class="filter-label" for="catalogSearch">Cari Nama / Deskripsi</label>
                    <form action="{{ route('products.index') }}" method="GET">
                        @if ($selectedCategory)
                            <input type="hidden" name="category" value="{{ $selectedCategory->slug }}">
                        @endif
                        @if (request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        <div style="position: relative;">
                            <input 
                                type="search" 
                                id="catalogSearch"
                                name="search" 
                                value="{{ $search }}" 
                                placeholder="Cari baju, jaket..." 
                                class="form-control"
                                style="padding-right: 38px;"
                            >
                            <button type="submit" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--slate-400);">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Category Filter --}}
                <div class="filter-group">
                    <span class="filter-label">Kategori Pakaian</span>
                    <ul class="sidebar-category-list">
                        <li class="sidebar-category-item {{ !$selectedCategory ? 'active' : '' }}">
                            <a href="{{ route('products.index', array_filter(['search' => $search, 'sort' => request('sort')])) }}">
                                <span>Semua Kategori</span>
                                <span style="font-size: 0.8rem; color: var(--slate-400);">{{ \App\Models\Product::count() }}</span>
                            </a>
                        </li>
                        @foreach ($categories as $category)
                            <li class="sidebar-category-item {{ $selectedCategory && $selectedCategory->id === $category->id ? 'active' : '' }}">
                                <a href="{{ route('products.index', array_filter(['category' => $category->slug, 'search' => $search, 'sort' => request('sort')])) }}">
                                    <span>{{ $category->name }}</span>
                                    <span style="font-size: 0.8rem; color: var(--slate-400);">{{ $category->products_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Active Filters Reset --}}
                @if ($search || $selectedCategory)
                    <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--slate-100);">
                        <a href="{{ route('products.index') }}" class="btn btn-outline" style="width: 100%; font-size: 0.85rem; padding: 10px;">
                            Reset Semua Filter
                        </a>
                    </div>
                @endif
            </aside>

            {{-- Right Main Area: Products Grid --}}
            <main>
                {{-- Header Summary & Sort --}}
                <div class="catalog-header">
                    <div>
                        <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--slate-900);">
                            @if ($selectedCategory)
                                Kategori: {{ $selectedCategory->name }}
                            @elseif ($search)
                                Hasil Pencarian: "{{ $search }}"
                            @else
                                Seluruh Katalog Produk
                            @endif
                        </h1>
                        <p class="catalog-count">
                            Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
                        </p>
                    </div>

                    {{-- Sort Dropdown --}}
                    <div class="catalog-sort">
                        <form id="sortForm" action="{{ route('products.index') }}" method="GET">
                            @if ($selectedCategory)
                                <input type="hidden" name="category" value="{{ $selectedCategory->slug }}">
                            @endif
                            @if ($search)
                                <input type="hidden" name="search" value="{{ $search }}">
                            @endif
                            <select name="sort" onchange="document.getElementById('sortForm').submit()">
                                <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                                <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                                <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                            </select>
                        </form>
                    </div>
                </div>

                {{-- Product Grid --}}
                @if ($products->count() > 0)
                    <div class="product-grid product-grid-3">
                        @foreach ($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="pagination-wrap">
                        {{ $products->links() }}
                    </div>
                @else
                    <x-empty-state 
                        title="Produk Tidak Ditemukan"
                        message="Kami tidak menemukan produk yang cocok dengan kata kunci atau filter pilihan Anda. Silakan coba kata kunci lain."
                        :resetUrl="route('products.index')"
                    />
                @endif
            </main>
        </div>
    </div>
@endsection
