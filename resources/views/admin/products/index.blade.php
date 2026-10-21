@extends('layouts.admin')

@section('title', 'Daftar Produk - Admin PT Imersa')
@section('header_title', 'Kelola Produk Katalog')

@section('content')
    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Daftar Seluruh Produk</h3>
                <p style="font-size: 0.85rem; color: var(--slate-500);">Menampilkan total {{ $products->total() }} produk pakaian</p>
            </div>
            <div>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="padding: 9px 18px; font-size: 0.875rem;">
                    + Tambah Produk Baru
                </a>
            </div>
        </div>

        {{-- Filter & Search Form --}}
        <form action="{{ route('admin.products.index') }}" method="GET" style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
            <div style="flex-grow: 1; min-width: 240px;">
                <input 
                    type="search" 
                    name="search" 
                    value="{{ $search }}" 
                    placeholder="Cari nama produk / deskripsi..." 
                    class="form-control"
                >
            </div>
            <div style="min-width: 200px;">
                <select name="category_id" class="form-control">
                    <option value="">-- Semua Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-secondary" style="padding: 10px 18px;">
                Filter
            </button>
            @if ($search || $categoryId)
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline" style="padding: 10px 14px;">
                    Reset
                </a>
            @endif
        </form>

        {{-- Products Table --}}
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
                        <th style="width: 120px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="table-thumb" onerror="handleImageError(this)">
                            </td>
                            <td>
                                <div style="font-weight: 700; color: var(--slate-900);">{{ $product->name }}</div>
                                <div style="display: flex; gap: 6px; align-items: center; margin-top: 2px;">
                                    <span style="font-size: 0.75rem; color: var(--slate-400);">slug: {{ $product->slug }}</span>
                                    @if ($product->is_featured)
                                        <span style="font-size: 0.7rem; font-weight: 700; background-color: var(--primary-light); color: var(--primary); padding: 1px 6px; border-radius: var(--radius-sm);">Unggulan</span>
                                    @endif
                                </div>
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
                                <span style="color: var(--amber-500); font-weight: 700;">★ {{ number_format($product->rating, 1) }}</span>
                                <span style="font-size: 0.75rem; color: var(--slate-400);">({{ $product->reviews_count ?? $product->reviews->count() }})</span>
                            </td>
                            <td>
                                <a href="{{ $product->shopee_url }}" target="_blank" rel="noopener noreferrer" style="color: var(--shopee); font-size: 0.8rem; font-weight: 600; text-decoration: underline;">
                                    Shopee Link ↗
                                </a>
                            </td>
                            <td style="text-align: right;">
                                <div class="table-actions" style="justify-content: flex-end;">
                                    <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="btn-action" title="Lihat di Web">
                                        👁
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-action" title="Edit Produk">
                                        ✎
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ $product->name }}? Seluruh ulasan terkait produk ini juga akan terhapus.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete" title="Hapus Produk">
                                            🗑
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--slate-400);">
                                Tidak ada produk yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 24px;">
            {{ $products->links() }}
        </div>
    </div>
@endsection
