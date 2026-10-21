@extends('layouts.admin')

@section('title', 'Ulasan Pembeli - Admin PT Imersa')
@section('header_title', 'Kelola Ulasan Pelanggan')

@section('content')
    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Daftar Ulasan & Testimoni</h3>
                <p style="font-size: 0.85rem; color: var(--slate-500);">Menampilkan seluruh ulasan pembeli yang terhubung dengan produk katalog.</p>
            </div>
            <div>
                <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary" style="padding: 9px 18px; font-size: 0.875rem;">
                    + Tambah Ulasan
                </a>
            </div>
        </div>

        {{-- Filter by Product --}}
        <form action="{{ route('admin.reviews.index') }}" method="GET" style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
            <div style="flex-grow: 1; max-width: 320px;">
                <select name="product_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Semua Produk --</option>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}" {{ $productId == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if ($productId)
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline" style="padding: 10px 14px;">
                    Reset Filter
                </a>
            @endif
        </form>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">Avatar</th>
                        <th>Nama Pembeli</th>
                        <th>Produk</th>
                        <th>Rating</th>
                        <th>Isi Ulasan</th>
                        <th style="width: 70px;">Foto</th>
                        <th style="width: 100px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $rev)
                        <tr>
                            <td>
                                <img 
                                    src="{{ $rev->avatar_url ?? asset('images/avatar-placeholder.svg') }}" 
                                    alt="{{ $rev->customer_name }}" 
                                    style="width: 38px; height: 38px; border-radius: var(--radius-full); object-fit: cover;"
                                    onerror="handleAvatarError(this)"
                                >
                            </td>
                            <td>
                                <div style="font-weight: 700; color: var(--slate-900);">{{ $rev->customer_name }}</div>
                                <span style="font-size: 0.75rem; color: var(--slate-400);">{{ $rev->created_at ? $rev->created_at->format('d/m/Y') : '-' }}</span>
                            </td>
                            <td>
                                @if ($rev->product)
                                    <a href="{{ route('products.show', $rev->product->slug) }}" target="_blank" style="font-weight: 600; color: var(--primary);">
                                        {{ $rev->product->name }}
                                    </a>
                                @else
                                    <span style="color: var(--slate-400);">-</span>
                                @endif
                            </td>
                            <td>
                                <span style="color: var(--amber-500); font-weight: 700;">★ {{ $rev->rating }}</span>
                            </td>
                            <td style="font-size: 0.875rem; color: var(--slate-700); max-width: 320px;">
                                {{ $rev->review_text }}
                            </td>
                            <td>
                                @if ($rev->photo_url)
                                    <img 
                                        src="{{ $rev->photo_url }}" 
                                        alt="Foto" 
                                        style="width: 44px; height: 44px; border-radius: var(--radius-sm); object-fit: cover; border: 1px solid var(--slate-200);"
                                        onerror="this.style.display='none'"
                                    >
                                @else
                                    <span style="font-size: 0.75rem; color: var(--slate-400);">-</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div class="table-actions" style="justify-content: flex-end;">
                                    <a href="{{ route('admin.reviews.edit', $rev->id) }}" class="btn-action" title="Edit Ulasan">
                                        ✎
                                    </a>
                                    <form action="{{ route('admin.reviews.destroy', $rev->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete" title="Hapus Ulasan">
                                            🗑
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 32px; color: var(--slate-400);">
                                Belum ada ulasan yang sesuai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 24px;">
            {{ $reviews->links() }}
        </div>
    </div>
@endsection
