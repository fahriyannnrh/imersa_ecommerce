@extends('layouts.admin')

@section('title', 'Edit Ulasan - Admin PT Imersa')
@section('header_title', 'Edit Ulasan Pembeli')

@section('content')
    <div class="admin-card" style="max-width: 760px;">
        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Edit Ulasan: {{ $review->customer_name }}</h3>
                <p style="font-size: 0.85rem; color: var(--slate-500);">Perbarui teks, rating bintang, atau URL foto ulasan.</p>
            </div>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline" style="padding: 8px 14px; font-size: 0.85rem;">
                &larr; Kembali ke Daftar
            </a>
        </div>

        <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="product_id" class="form-label">Produk Terkait <span class="required">*</span></label>
                    <select id="product_id" name="product_id" class="form-control" required>
                        @foreach ($products as $p)
                            <option value="{{ $p->id }}" {{ old('product_id', $review->product_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="rating" class="form-label">Rating Bintang <span class="required">*</span></label>
                    <select id="rating" name="rating" class="form-control" required>
                        <option value="5" {{ old('rating', $review->rating) == 5 ? 'selected' : '' }}>★★★★★ (5 Bintang)</option>
                        <option value="4" {{ old('rating', $review->rating) == 4 ? 'selected' : '' }}>★★★★☆ (4 Bintang)</option>
                        <option value="3" {{ old('rating', $review->rating) == 3 ? 'selected' : '' }}>★★★☆☆ (3 Bintang)</option>
                        <option value="2" {{ old('rating', $review->rating) == 2 ? 'selected' : '' }}>★★☆☆☆ (2 Bintang)</option>
                        <option value="1" {{ old('rating', $review->rating) == 1 ? 'selected' : '' }}>★☆☆☆☆ (1 Bintang)</option>
                    </select>
                    @error('rating')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="customer_name" class="form-label">Nama Pelanggan / Pembeli <span class="required">*</span></label>
                <input 
                    type="text" 
                    id="customer_name" 
                    name="customer_name" 
                    value="{{ old('customer_name', $review->customer_name) }}" 
                    class="form-control" 
                    required 
                >
                @error('customer_name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="review_text" class="form-label">Isi Ulasan <span class="required">*</span></label>
                <textarea 
                    id="review_text" 
                    name="review_text" 
                    class="form-control" 
                    rows="4" 
                    required
                >{{ old('review_text', $review->review_text) }}</textarea>
                @error('review_text')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="avatar_url" class="form-label">URL Avatar Pembeli (Opsional)</label>
                <input 
                    type="url" 
                    id="avatar_url" 
                    name="avatar_url" 
                    value="{{ old('avatar_url', $review->avatar_url) }}" 
                    class="form-control" 
                >
                @error('avatar_url')
                    <div class="form-error">{{ $message }}</div>
                @enderror

                <div class="image-preview-wrapper" style="margin-top: 10px;">
                    <div class="image-preview-box" style="width: 48px; height: 48px; border-radius: var(--radius-full);">
                        <img 
                            id="avatar_url_preview" 
                            src="{{ old('avatar_url', $review->avatar_url) ?? asset('images/avatar-placeholder.svg') }}" 
                            alt="Preview Avatar"
                            onerror="handleAvatarError(this)"
                        >
                    </div>
                    <span style="font-size: 0.8rem; color: var(--slate-500);">Pratinjau Avatar Pembeli</span>
                </div>
            </div>

            <div class="form-group">
                <label for="photo_url" class="form-label">URL Foto Ulasan Produk (Opsional)</label>
                <input 
                    type="url" 
                    id="photo_url" 
                    name="photo_url" 
                    value="{{ old('photo_url', $review->photo_url) }}" 
                    class="form-control" 
                >
                @error('photo_url')
                    <div class="form-error">{{ $message }}</div>
                @enderror

                <div class="image-preview-wrapper" style="margin-top: 10px;">
                    <div class="image-preview-box" style="width: 72px; height: 72px;">
                        <img 
                            id="photo_url_preview" 
                            src="{{ old('photo_url', $review->photo_url) ?? asset('images/placeholder.svg') }}" 
                            alt="Preview Foto Ulasan"
                            onerror="handleImageError(this)"
                        >
                    </div>
                    <span style="font-size: 0.8rem; color: var(--slate-500);">Pratinjau Foto Testimoni</span>
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 32px; border-top: 1px solid var(--slate-200); padding-top: 20px;">
                <button type="submit" class="btn btn-primary">
                    Perbarui Ulasan
                </button>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
