@extends('layouts.admin')

@section('title', 'Tambah Kategori Baru - Admin PT Imersa')
@section('header_title', 'Tambah Kategori')

@section('content')
    <div class="admin-card" style="max-width: 680px;">
        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Formulir Kategori Baru</h3>
                <p style="font-size: 0.85rem; color: var(--slate-500);">Tambahkan kelompok pakaian baru untuk katalog PT Imersa.</p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline" style="padding: 8px 14px; font-size: 0.85rem;">
                &larr; Kembali ke Daftar
            </a>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nama Kategori <span class="required">*</span></label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}" 
                    class="form-control" 
                    required 
                    placeholder="Contoh: Jaket & Outerwear"
                >
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="slug" class="form-label">Slug URL (Opsional)</label>
                <input 
                    type="text" 
                    id="slug" 
                    name="slug" 
                    value="{{ old('slug') }}" 
                    class="form-control" 
                    placeholder="Contoh: jaket-outerwear (Kosongkan untuk otomatis)"
                >
                <span class="form-help">Jika dikosongkan, slug akan digenerate otomatis dari nama kategori.</span>
                @error('slug')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi Kategori</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control" 
                    rows="4" 
                    placeholder="Deskripsi singkat mengenai jenis produk yang termasuk dalam kategori ini..."
                >{{ old('description') }}</textarea>
                @error('description')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 12px; margin-top: 32px; border-top: 1px solid var(--slate-200); padding-top: 20px;">
                <button type="submit" class="btn btn-primary">
                    Simpan Kategori Baru
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
