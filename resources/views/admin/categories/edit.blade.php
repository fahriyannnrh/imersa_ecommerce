@extends('layouts.admin')

@section('title', 'Edit Kategori - ' . $category->name)
@section('header_title', 'Edit Kategori')

@section('content')
    <div class="admin-card" style="max-width: 680px;">
        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Edit Kategori: {{ $category->name }}</h3>
                <p style="font-size: 0.85rem; color: var(--slate-500);">Perbarui nama atau deskripsi kategori.</p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline" style="padding: 8px 14px; font-size: 0.85rem;">
                &larr; Kembali ke Daftar
            </a>
        </div>

        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="form-label">Nama Kategori <span class="required">*</span></label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $category->name) }}" 
                    class="form-control" 
                    required 
                >
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="slug" class="form-label">Slug URL</label>
                <input 
                    type="text" 
                    id="slug" 
                    name="slug" 
                    value="{{ old('slug', $category->slug) }}" 
                    class="form-control" 
                >
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
                >{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 12px; margin-top: 32px; border-top: 1px solid var(--slate-200); padding-top: 20px;">
                <button type="submit" class="btn btn-primary">
                    Perbarui Kategori
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
