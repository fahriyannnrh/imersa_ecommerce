@extends('layouts.admin')

@section('title', 'Kategori Pakaian - Admin PT Imersa')
@section('header_title', 'Kelola Kategori Pakaian')

@section('content')
    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Daftar Kategori Pakaian</h3>
                <p style="font-size: 0.85rem; color: var(--slate-500);">Kategori digunakan untuk pengelompokan produk katalog dan navigasi website.</p>
            </div>
            <div>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary" style="padding: 9px 18px; font-size: 0.875rem;">
                    + Tambah Kategori
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Produk</th>
                        <th style="width: 120px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $cat)
                        <tr>
                            <td style="color: var(--slate-400); font-weight: 600;">#{{ $cat->id }}</td>
                            <td>
                                <div style="font-weight: 700; color: var(--slate-900);">{{ $cat->name }}</div>
                            </td>
                            <td>
                                <span style="font-family: monospace; font-size: 0.85rem; color: var(--slate-600); background-color: var(--slate-100); padding: 2px 8px; border-radius: var(--radius-sm);">
                                    {{ $cat->slug }}
                                </span>
                            </td>
                            <td style="font-size: 0.85rem; color: var(--slate-600); max-width: 320px;">
                                {{ Str::limit($cat->description, 70) ?? '-' }}
                            </td>
                            <td>
                                <span style="font-weight: 700; color: var(--primary); background-color: var(--primary-light); padding: 3px 10px; border-radius: var(--radius-full); font-size: 0.8rem;">
                                    {{ $cat->products_count }} Produk
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div class="table-actions" style="justify-content: flex-end;">
                                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}" target="_blank" class="btn-action" title="Lihat di Web">
                                        👁
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn-action" title="Edit Kategori">
                                        ✎
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $cat->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete" title="Hapus Kategori">
                                            🗑
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 32px; color: var(--slate-400);">
                                Belum ada kategori yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
