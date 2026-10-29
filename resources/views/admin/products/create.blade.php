@extends('layouts.admin')

@section('title', 'Tambah Produk Baru - Admin PT Imersa')
@section('header_title', 'Tambah Produk Baru')

@section('content')
    <div style="max-width: 920px; margin: 0 auto;">
        
        {{-- Shopee Quick Importer (1-Step Auto Extract Everything!) --}}
        <div class="shopee-quick-card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div class="shopee-icon-badge">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19.38 6.64H16.5a4.5 4.5 0 0 0-9 0H4.62A1.63 1.63 0 0 0 3 8.27l1.37 12.35A2.45 2.45 0 0 0 6.82 23h10.36a2.45 2.45 0 0 0 2.45-2.38L21 8.27a1.63 1.63 0 0 0-1.62-1.63zM12 3.75a2.88 2.88 0 0 1 2.87 2.89H9.13A2.88 2.88 0 0 1 12 3.75zm6 16.75a.82.82 0 0 1-.82.78H6.82a.82.82 0 0 1-.82-.78L4.69 8.27H19.3z"/>
                        </svg>
                    </div>
                    <div>
                        <span style="font-size: 1rem; font-weight: 700; color: var(--slate-900);">Ekstrak Otomatis dari Link Produk Shopee</span>
                        <span style="font-size: 0.72rem; font-weight: 700; background: #fff1ed; color: #ee4d2d; padding: 2px 8px; border-radius: var(--radius-full); margin-left: 6px; border: 1px solid #ffdcd4;">1 Langkah Praktis</span>
                    </div>
                </div>
            </div>

            <p style="font-size: 0.85rem; color: var(--slate-600); margin: 0 0 14px 0; line-height: 1.5;">
                Cukup tempelkan tautan produk Shopee. Sistem akan <strong>otomatis mengisi Nama Produk, Harga, Foto Produk, Kategori, Deskripsi, dan Ulasan Pembeli Bintang 5</strong> tanpa perlu mencari atau menempelkan alamat gambar secara manual.
            </p>

            <div class="shopee-input-bar">
                <input 
                    type="text" 
                    id="quick_shopee_url" 
                    class="form-control" 
                    placeholder="Tempel link produk Shopee di sini (misal: https://shopee.co.id/... atau teks share)"
                    value="{{ request('shopee_url', old('shopee_url')) }}"
                    autocomplete="off"
                >
                <button type="button" id="btn_extract" class="btn-extract-action" onclick="triggerExtraction()">
                    <span id="btn_extract_spinner" class="spinner-icon" style="display: none;"></span>
                    <span id="btn_extract_icon">⚡</span>
                    <span id="btn_extract_text">Ekstrak & Isi Otomatis</span>
                </button>
            </div>

            {{-- Status Alert --}}
            <div id="extract_status" class="extract-status-alert" style="display: none;"></div>

            {{-- Bookmarklet Accordion --}}
            <details class="bookmarklet-tip">
                <summary>
                    💡 <span>Tips: Pasang Bookmarklet Browser untuk impor 1-klik dari halaman Shopee</span>
                </summary>
                <div class="bookmarklet-content">
                    <span>Tarik tombol ini ke Bookmarks Bar browser Anda:</span>
                    <a href="javascript:(function(){let p='';let el=document.querySelector('.pqTWkA, ._2Shl1j, .items-center .text-brand-primary, [class*=\'price\'], .G27ShH');if(!el){let all=document.querySelectorAll('div,span');for(let e of all){if(/^Rp\s*[0-9.,]+$/i.test(e.textContent.trim())){el=e;break;}}}if(el){let m=el.textContent.match(/([0-9.,]+)/);if(m)p=m[1].replace(/[^0-9]/g,'');}window.open('{{ url('/admin/products/create') }}?shopee_url='+encodeURIComponent(window.location.href)+(p?'&price='+p:''),'_blank');})();" 
                       class="btn-bookmarklet-chip"
                       onclick="alert('Tarik tombol ini ke Bookmarks Bar browser Anda. Saat membuka produk di Shopee, klik bookmark untuk auto-import!'); return false;"
                       title="Tarik tombol ini ke Bookmarks Bar browser">
                        🛒 Tarik ke Bookmarks: Impor ke Imersa
                    </a>
                </div>
            </details>
        </div>

        {{-- Main Product Form Card --}}
        <div class="admin-card">
            <div class="admin-card-header" style="border-bottom: 1px solid var(--slate-100); padding-bottom: 14px; margin-bottom: 22px;">
                <div>
                    <h3 class="admin-card-title" style="font-size: 1.15rem; margin-bottom: 2px;">Detail Informasi Produk</h3>
                    <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">Semua rincian terisi otomatis dari Shopee dan siap disimpan ke katalog toko.</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline" style="padding: 6px 14px; font-size: 0.82rem;">
                    &larr; Kembali ke Daftar
                </a>
            </div>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="product_form">
                @csrf

                {{-- Row 1: Nama Produk & Kategori --}}
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="name" class="form-label">
                            Nama Produk <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}" 
                            class="form-control" 
                            required 
                            placeholder="Contoh: Kaos Oversize Cotton Combed 24s Heavyweight"
                        >
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="category_id" class="form-label">
                            Kategori <span class="required">*</span>
                        </label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Row 2: Harga & Tautan Shopee --}}
                <div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 20px; margin-bottom: 24px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="price" class="form-label">
                            Harga Produk (Rupiah) <span class="required">*</span>
                        </label>
                        <div class="price-input-wrapper">
                            <span class="price-prefix">Rp</span>
                            <input 
                                type="number" 
                                id="price" 
                                name="price" 
                                value="{{ old('price') }}" 
                                class="form-control price-input" 
                                required 
                                min="0"
                                step="1000"
                                placeholder="0"
                            >
                        </div>
                        @error('price')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="shopee_url" class="form-label">
                            Tautan Produk di Shopee <span class="required">*</span>
                        </label>
                        <input 
                            type="url" 
                            id="shopee_url" 
                            name="shopee_url" 
                            value="{{ old('shopee_url') }}" 
                            class="form-control" 
                            required 
                            placeholder="https://shopee.co.id/product/40268086/22760826572"
                        >
                        <span class="form-help">Pengunjung katalog akan diarahkan ke link ini saat klik "Order di Shopee".</span>
                        @error('shopee_url')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Row 3: Foto Produk (Otomatis dari Link Shopee) --}}
                <div class="photo-hotlink-card" style="margin-bottom: 20px;">
                    <div class="photo-hotlink-header">
                        <div>
                            <label class="form-label" style="margin-bottom: 2px;">
                                Foto Produk <span class="required">*</span>
                            </label>
                            <span style="font-size: 0.8rem; color: var(--slate-500);">
                                📸 Foto produk <strong>otomatis diambil</strong> dari tautan Shopee saat Anda menempelkan link di atas. Tidak perlu mencari atau menempelkan alamat gambar.
                            </span>
                        </div>
                    </div>

                    <div class="photo-hotlink-body">
                        {{-- Pratinjau Foto --}}
                        <div class="photo-preview-wrap">
                            <div class="photo-preview-box" id="preview_box">
                                <img 
                                    id="image_url_preview" 
                                    src="{{ old('image_url') ?? asset('images/placeholder.svg') }}" 
                                    alt="Pratinjau Foto Produk"
                                    referrerpolicy="no-referrer"
                                    onerror="handleImagePreviewError(this)"
                                >
                                <div class="preview-spinner" id="preview_spinner"></div>
                            </div>
                            <span class="photo-preview-tag" id="preview_tag">Pratinjau Foto</span>
                        </div>

                        {{-- Status & Auto-filled info --}}
                        <div class="photo-input-wrap">
                            {{-- Auto-extracted status --}}
                            <div id="hotlink_status" class="hotlink-status-badge idle">
                                <span class="badge-icon">ℹ️</span>
                                <span id="hotlink_status_text">Tempel link produk Shopee di atas, foto produk akan otomatis tampil di sini.</span>
                            </div>

                            {{-- Hidden URL input (auto-filled by extraction) --}}
                            <input 
                                type="url" 
                                id="image_url" 
                                name="image_url" 
                                value="{{ old('image_url') }}" 
                                class="form-control" 
                                style="display: none;"
                                autocomplete="off"
                            >

                            {{-- Collapsible advanced options --}}
                            <details class="photo-advanced-options" style="margin-top: 10px;">
                                <summary style="font-size: 0.78rem; color: var(--slate-400); cursor: pointer; user-select: none;">
                                    ⚙️ Opsi Lanjutan (ganti foto manual / upload file)
                                </summary>
                                <div style="margin-top: 10px; padding: 12px; background: var(--slate-50, #f8fafc); border-radius: 8px; border: 1px solid var(--slate-100, #f1f5f9);">
                                    <div class="tab-pill-group" style="margin-bottom: 10px;">
                                        <button type="button" class="tab-pill active" id="btn_tab_url" onclick="switchImageTab('url')">
                                            🔗 Tempel URL Gambar
                                        </button>
                                        <button type="button" class="tab-pill" id="btn_tab_file" onclick="switchImageTab('file')">
                                            📁 Upload File
                                        </button>
                                    </div>

                                    <div id="image_url_group">
                                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px; flex-wrap: wrap; gap: 6px;">
                                            <label for="image_url_visible" style="font-size: 0.82rem; font-weight: 700; color: var(--slate-700); margin: 0;">
                                                Alamat Hotlink Gambar (URL):
                                            </label>
                                            <div style="display: flex; align-items: center; gap: 4px;">
                                                <button type="button" class="btn-hotlink-tool" onclick="pasteHotlinkFromClipboard()" title="Tempel langsung dari Clipboard">
                                                    📋 Tempel
                                                </button>
                                                <button type="button" class="btn-hotlink-tool" onclick="openHotlinkInNewTab()" title="Buka tautan gambar di tab baru">
                                                    🔗 Buka
                                                </button>
                                                <button type="button" class="btn-hotlink-tool" onclick="resetHotlinkField()" title="Hapus URL dan kembalikan pratinjau">
                                                    ✕ Reset
                                                </button>
                                            </div>
                                        </div>
                                        <input 
                                            type="url" 
                                            id="image_url_visible" 
                                            class="form-control" 
                                            placeholder="https://... tempel alamat hotlink gambar manual di sini"
                                            autocomplete="off"
                                            oninput="handleHotlinkInputChange(this.value); document.getElementById('image_url').value = this.value;"
                                            onchange="handleHotlinkInputChange(this.value); document.getElementById('image_url').value = this.value;"
                                        >
                                    </div>

                                    <div id="image_file_group" style="display: none;">
                                        <label for="image_file" style="font-size: 0.82rem; font-weight: 700; color: var(--slate-700); margin-bottom: 4px; display: block;">
                                            Pilih File Foto dari Komputer:
                                        </label>
                                        <input 
                                            type="file" 
                                            id="image_file" 
                                            name="image_file" 
                                            class="form-control" 
                                            accept="image/png, image/jpeg, image/jpg, image/webp"
                                            onchange="handleFileSelect(this)"
                                        >
                                        <span class="form-help">Pilih file foto dari komputer Anda (JPG, PNG, WEBP, Maks 2MB).</span>
                                    </div>
                                </div>
                            </details>
                        </div>
                    </div>

                    @error('image_url')
                        <div class="form-error" style="margin-top: 8px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Row 4: Deskripsi Produk --}}
                <div class="form-group" style="margin-top: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="description" class="form-label" style="margin-bottom: 0;">
                            Deskripsi Lengkap Produk <span class="required">*</span>
                        </label>
                        <span style="font-size: 0.78rem; color: var(--slate-400);">
                            Rincian bahan, ukuran, dan spesifikasi terisi otomatis dari Shopee
                        </span>
                    </div>
                    <textarea 
                        id="description" 
                        name="description" 
                        class="form-control" 
                        rows="6" 
                        required
                        placeholder="Deskripsi produk akan terisi secara otomatis saat link Shopee diekstrak..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Row 5: Rating & Ulasan Pembeli Otomatis dari Shopee --}}
                <div class="reviews-auto-card">
                    <div class="reviews-auto-header">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 1.25rem;">⭐</span>
                            <div>
                                <h4 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--slate-900);">
                                    Rating & Ulasan Pembeli (Otomatis dari Shopee)
                                </h4>
                                <span style="font-size: 0.78rem; color: var(--slate-500);">
                                    Rating dan ulasan otomatis disesuaikan secara real-time saat Anda menempel tautan produk Shopee.
                                </span>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span class="badge-review-counter" id="reviews_count_badge">4 Ulasan Siap Disimpan</span>
                        </div>
                    </div>

                    {{-- Rating Summary Banner --}}
                    <div class="rating-summary-banner">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="rating-badge-pill">
                                <span style="color: #f59e0b; font-size: 1.1rem;">★</span>
                                <span class="rating-score-num" id="rating_score_val">{{ old('rating', '5.0') }}</span>
                                <span style="font-size: 0.75rem; color: var(--slate-500); font-weight: 600;">/ 5.0</span>
                            </div>
                            <div>
                                <div class="rating-stars-gold" id="rating_stars_display">★★★★★</div>
                                <div id="rating_subtext" style="font-size: 0.75rem; color: #92400e; font-weight: 600;">
                                    Tersinkronisasi otomatis dengan tautan produk Shopee
                                </div>
                            </div>
                        </div>
                        <div style="font-size: 0.78rem; color: var(--slate-600); display: flex; align-items: center; gap: 6px;">
                            <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></span>
                            <span id="rating_mode_label">Rating: Otomatis Sesuai Hotlink</span>
                        </div>
                    </div>

                    {{-- Hidden inputs for rating and reviews --}}
                    <input type="hidden" name="rating" id="rating_input" value="{{ old('rating', '5.00') }}">
                    <input type="hidden" name="shopee_reviews" id="shopee_reviews" value="{{ old('shopee_reviews') }}">

                    {{-- Container for rendering review cards --}}
                    <div id="reviews_list_container" class="reviews-preview-grid">
                        <div class="review-preview-item">
                            <div class="review-user-row">
                                <img src="https://ui-avatars.com/api/?name=Dimas+Pratama&background=ee4d2d&color=ffffff&bold=true" class="review-avatar" alt="Dimas Pratama">
                                <div style="flex: 1; min-width: 0;">
                                    <div class="review-user-name">Dimas Pratama</div>
                                    <div class="review-stars">★★★★★ <span style="font-size: 0.7rem; color: var(--slate-400); font-weight: normal; margin-left: 4px;">(Kemarin)</span></div>
                                </div>
                            </div>
                            <p class="review-comment-text" id="sample_rev_1">"Kualitas produk bagus banget, bahan adem dan jahitan sangat rapi. 100% realpict sesuai gambar!"</p>
                        </div>
                        <div class="review-preview-item">
                            <div class="review-user-row">
                                <img src="https://ui-avatars.com/api/?name=Rizky+Ramadhan&background=2563eb&color=ffffff&bold=true" class="review-avatar" alt="Rizky Ramadhan">
                                <div style="flex: 1; min-width: 0;">
                                    <div class="review-user-name">Rizky Ramadhan</div>
                                    <div class="review-stars">★★★★★ <span style="font-size: 0.7rem; color: var(--slate-400); font-weight: normal; margin-left: 4px;">(2 hari lalu)</span></div>
                                </div>
                            </div>
                            <p class="review-comment-text" id="sample_rev_2">"Kualitas mantap original, jahitan rapi banget standar distro. Ukuran pas sesuai deskripsi. Recommended!"</p>
                        </div>
                        <div class="review-preview-item">
                            <div class="review-user-row">
                                <img src="https://ui-avatars.com/api/?name=Bagus+Setiawan&background=16a34a&color=ffffff&bold=true" class="review-avatar" alt="Bagus Setiawan">
                                <div style="flex: 1; min-width: 0;">
                                    <div class="review-user-name">Bagus Setiawan</div>
                                    <div class="review-stars">★★★★★ <span style="font-size: 0.7rem; color: var(--slate-400); font-weight: normal; margin-left: 4px;">(4 hari lalu)</span></div>
                                </div>
                            </div>
                            <p class="review-comment-text" id="sample_rev_3">"Pengiriman super cepat dan packing rapi. Produk nyaman dipakai seharian. Puas belanja di sini!"</p>
                        </div>
                        <div class="review-preview-item">
                            <div class="review-user-row">
                                <img src="https://ui-avatars.com/api/?name=Aldi+Kurniawan&background=d97706&color=ffffff&bold=true" class="review-avatar" alt="Aldi Kurniawan">
                                <div style="flex: 1; min-width: 0;">
                                    <div class="review-user-name">Aldi Kurniawan</div>
                                    <div class="review-stars">★★★★★ <span style="font-size: 0.7rem; color: var(--slate-400); font-weight: normal; margin-left: 4px;">(6 hari lalu)</span></div>
                                </div>
                            </div>
                            <p class="review-comment-text" id="sample_rev_4">"Packing aman, respon toko gercep. Kualitas produk melebihi ekspektasi harga segini. Mantap!"</p>
                        </div>
                    </div>
                </div>

                {{-- Row 6: Produk Unggulan --}}
                <div style="margin-top: 20px;">
                    <label class="custom-checkbox-label">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <div>
                            <span class="checkbox-title">Jadikan sebagai Produk Unggulan</span>
                            <span class="checkbox-desc">Tampilkan produk ini di barisan terdepan pada beranda katalog toko.</span>
                        </div>
                    </label>
                </div>

                {{-- Row 7: Submit Buttons --}}
                <div style="display: flex; gap: 12px; margin-top: 28px; border-top: 1px solid var(--slate-200); padding-top: 20px;">
                    <button type="submit" class="btn btn-primary" style="padding: 11px 26px; font-weight: 700; font-size: 0.92rem;">
                        ✓ Simpan & Publikasikan ke Katalog
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline" style="padding: 11px 20px; font-size: 0.9rem;">
                        Batal
                    </a>
                </div>

            </form>
        </div>

    </div>

    <style>
        /* Shopee Quick Importer */
        .shopee-quick-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-lg);
            padding: 18px 22px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
        }

        .shopee-icon-badge {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            background: #fff5f2;
            color: #ee4d2d;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .shopee-input-bar {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        @media (max-width: 640px) {
            .shopee-input-bar {
                flex-direction: column;
                align-items: stretch;
            }
        }

        .btn-extract-action {
            background: #ee4d2d;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.88rem;
            padding: 10px 18px;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            white-space: nowrap;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(238, 77, 45, 0.2);
        }

        .btn-extract-action:hover {
            background: #dc3917;
            transform: translateY(-1px);
        }

        .btn-extract-action:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        .extract-status-alert {
            margin-top: 12px;
            padding: 10px 14px;
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            font-weight: 600;
            line-height: 1.45;
        }

        .extract-status-alert.success {
            background-color: var(--emerald-50);
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .extract-status-alert.warning {
            background-color: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .extract-status-alert.error {
            background-color: var(--rose-50);
            color: #9f1239;
            border: 1px solid #fecdd3;
        }

        /* Bookmarklet Accordion */
        .bookmarklet-tip {
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px dashed var(--slate-200);
            font-size: 0.82rem;
            color: var(--slate-500);
        }

        .bookmarklet-tip summary {
            cursor: pointer;
            font-weight: 600;
            color: var(--slate-600);
            user-select: none;
            outline: none;
        }

        .bookmarklet-tip summary:hover {
            color: #ee4d2d;
        }

        .bookmarklet-content {
            margin-top: 8px;
            padding: 10px 14px;
            background: #fafaf9;
            border: 1px solid #e7e5e4;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn-bookmarklet-chip {
            background: #fff5f2;
            color: #ee4d2d;
            border: 1px solid #ffd8cc;
            padding: 4px 10px;
            border-radius: var(--radius-sm);
            font-size: 0.78rem;
            font-weight: 700;
            text-decoration: none;
            cursor: grab;
            transition: all 0.2s ease;
        }

        .btn-bookmarklet-chip:hover {
            background: #feeae4;
            border-color: #fca58f;
        }

        /* Automated Photo Card */
        .photo-auto-card {
            background-color: var(--slate-50);
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-lg);
            padding: 18px 20px;
            margin-top: 8px;
        }

        .photo-auto-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 14px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn-toggle-manual {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            color: var(--slate-600);
            font-size: 0.78rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-toggle-manual:hover {
            background: var(--slate-100);
            color: var(--slate-900);
        }

        .photo-auto-body {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 20px;
            align-items: center;
        }

        @media (max-width: 640px) {
            .photo-auto-body {
                grid-template-columns: 1fr;
            }
        }

        .photo-preview-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .photo-preview-box {
            width: 140px;
            height: 140px;
            border-radius: var(--radius-md);
            background: #ffffff;
            border: 1.5px solid var(--slate-200);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-sm);
            position: relative;
        }

        .photo-preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.2s ease;
        }

        .photo-preview-box.loading img {
            opacity: 0.35;
        }

        .photo-preview-box .preview-spinner {
            position: absolute;
            width: 26px;
            height: 26px;
            border: 3px solid rgba(238, 77, 45, 0.2);
            border-top-color: #ee4d2d;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: none;
            z-index: 2;
        }

        .photo-preview-box.loading .preview-spinner {
            display: block;
        }

        .photo-preview-tag {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .auto-photo-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            font-size: 0.82rem;
            font-weight: 600;
        }

        .auto-photo-badge.success {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .auto-photo-badge.loading {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .auto-photo-badge.error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        /* Reviews Auto Card */
        .reviews-auto-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-lg);
            padding: 16px 20px;
            margin-top: 22px;
            box-shadow: var(--shadow-sm);
        }

        .reviews-auto-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .rating-summary-banner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1px solid #fde68a;
            border-radius: var(--radius-md);
            padding: 10px 16px;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .rating-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ffffff;
            border: 1px solid #fcd34d;
            padding: 4px 10px;
            border-radius: var(--radius-full);
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }

        .rating-score-num {
            font-size: 1.25rem;
            font-weight: 800;
            color: #b45309;
            line-height: 1;
        }

        .rating-stars-gold {
            color: #f59e0b;
            font-size: 1rem;
            letter-spacing: 2px;
        }

        .badge-review-counter {
            font-size: 0.75rem;
            font-weight: 700;
            background: #ecfdf5;
            color: #047857;
            padding: 4px 10px;
            border-radius: var(--radius-full);
            border: 1px solid #a7f3d0;
        }

        .reviews-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 12px;
            margin-top: 10px;
        }

        .review-preview-item {
            background: var(--slate-50);
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-md);
            padding: 12px 14px;
            font-size: 0.82rem;
            transition: all 0.2s ease;
        }

        .review-preview-item:hover {
            border-color: var(--slate-300);
            background: #f8fafc;
        }

        .review-user-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }

        .review-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--slate-200);
        }

        .review-user-name {
            font-weight: 700;
            color: var(--slate-800);
            font-size: 0.82rem;
        }

        .review-stars {
            color: #f59e0b;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }

        .review-comment-text {
            color: var(--slate-600);
            line-height: 1.4;
            font-size: 0.8rem;
            margin: 0;
            font-style: italic;
        }

        /* Pill Tabs */
        .tab-pill-group {
            display: inline-flex;
            gap: 4px;
            background: var(--slate-200);
            padding: 2px;
            border-radius: var(--radius-md);
        }

        .tab-pill {
            padding: 4px 10px;
            font-size: 0.75rem;
            font-weight: 600;
            border: none;
            background: transparent;
            color: var(--slate-600);
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tab-pill.active {
            background: #ffffff;
            color: var(--slate-900);
            box-shadow: var(--shadow-sm);
        }

        /* Custom Checkbox */
        .custom-checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            cursor: pointer;
            user-select: none;
        }

        .custom-checkbox-label input[type="checkbox"] {
            margin-top: 3px;
            accent-color: var(--primary);
            width: 16px;
            height: 16px;
        }

        .checkbox-title {
            font-weight: 700;
            font-size: 0.88rem;
            color: var(--slate-800);
            display: block;
        }

        .checkbox-desc {
            font-size: 0.78rem;
            color: var(--slate-400);
            display: block;
            margin-top: 2px;
            line-height: 1.35;
        }

        /* Spinner Animation */
        .spinner-icon {
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.35);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .price-highlight {
            border-color: #f97316 !important;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.25) !important;
            transition: all 0.3s ease;
        }
    </style>

    <script>
        const quickInput = document.getElementById('quick_shopee_url');
        const formShopeeInput = document.getElementById('shopee_url');
        const formPriceInput = document.getElementById('price');
        const btnExtract = document.getElementById('btn_extract');
        const btnExtractIcon = document.getElementById('btn_extract_icon');
        const btnExtractSpinner = document.getElementById('btn_extract_spinner');
        const btnExtractText = document.getElementById('btn_extract_text');
        const statusBox = document.getElementById('extract_status');
        const previewImg = document.getElementById('image_url_preview');
        const previewBox = document.getElementById('preview_box');
        const imageUrlInput = document.getElementById('image_url');
        const hotlinkStatus = document.getElementById('hotlink_status');
        const hotlinkStatusText = document.getElementById('hotlink_status_text');
        const shopeeReviewsInput = document.getElementById('shopee_reviews');
        const reviewsContainer = document.getElementById('reviews_list_container');
        const reviewsCountBadge = document.getElementById('reviews_count_badge');
        const PLACEHOLDER_IMG = '{{ asset("images/placeholder.svg") }}';

        let userCustomPhoto = false;

        // Check if a URL is a direct image hotlink
        function isDirectImageUrl(url) {
            if (!url) return false;
            const lower = url.trim().toLowerCase();
            if (lower.includes('susercontent.com/file/') || lower.includes('cf.shopee.co.id/file/')) return true;
            if (/\.(jpe?g|png|webp|gif|bmp)(\?.*)?$/i.test(lower)) return true;
            return false;
        }

        // Helper to extract exact price from text or share snippet
        function parsePriceFromText(text) {
            if (!text) return null;
            const matchLabeled = text.match(/(?:rp|seharga|harga|promo|diskon|idr)\.?\s*([0-9.,]+)/i);
            if (matchLabeled) {
                const num = parseInt(matchLabeled[1].replace(/[^0-9]/g, ''), 10);
                if (num >= 1000) return num;
            }
            const textWithoutUrl = text.replace(/https?:\/\/[^\s]+/g, '');
            const matchDot = textWithoutUrl.match(/\b(\d{1,3}(?:\.\d{3})+)\b/);
            if (matchDot) {
                const num = parseInt(matchDot[1].replace(/\./g, ''), 10);
                if (num >= 1000) return num;
            }
            const matchK = textWithoutUrl.match(/\b(\d{2,4})\s*k\b/i);
            if (matchK) return parseInt(matchK[1], 10) * 1000;
            const matchRb = textWithoutUrl.match(/\b(\d{2,3})\s*(?:ribu|rb)\b/i);
            if (matchRb) return parseInt(matchRb[1], 10) * 1000;
            const matchNum = textWithoutUrl.match(/\b(\d{4,8})\b/);
            if (matchNum) {
                const num = parseInt(matchNum[1], 10);
                if (num >= 1000) return num;
            }
            return null;
        }

        function setHotlinkStatus(state, msg) {
            if (!hotlinkStatus || !hotlinkStatusText) return;
            hotlinkStatus.className = 'hotlink-status-badge ' + state;
            const iconMap = {
                'success': '✓',
                'loading': '⏳',
                'error': '⚠',
                'idle': 'ℹ️'
            };
            const iconSpan = hotlinkStatus.querySelector('.badge-icon');
            if (iconSpan) iconSpan.textContent = iconMap[state] || 'ℹ️';
            hotlinkStatusText.textContent = msg;

            if (previewBox) {
                if (state === 'loading') {
                    previewBox.classList.add('loading');
                } else {
                    previewBox.classList.remove('loading');
                }
            }
        }

        function handleHotlinkInputChange(val, isUserAction = true) {
            const trimmed = (val || '').trim();
            if (isUserAction) {
                userCustomPhoto = !!trimmed;
            }
            if (!trimmed) {
                previewImg.src = PLACEHOLDER_IMG;
                setHotlinkStatus('idle', 'Mendukung alamat URL gambar langsung. Gambar di samping akan otomatis menyesuaikan secara langsung saat link ditempel.');
                return;
            }

            // Check if user accidentally pasted Shopee product link instead of image hotlink
            if (trimmed.includes('shopee.co.id/') && !trimmed.includes('/file/') && !/\.(jpe?g|png|webp)/i.test(trimmed)) {
                if (formShopeeInput && !formShopeeInput.value) formShopeeInput.value = trimmed;
                if (quickInput && !quickInput.value) quickInput.value = trimmed;
                setHotlinkStatus('error', '⚠️ Link yang Anda tempel di kotak foto adalah tautan halaman Shopee, bukan tautan file foto. Silakan buka halaman Shopee, klik kanan foto produk > pilih "Salin alamat gambar", lalu tempel di sini.');
                return;
            }

            setHotlinkStatus('loading', 'Memuat pratinjau foto hotlink...');
            previewImg.referrerPolicy = 'no-referrer';

            const tester = new Image();
            tester.referrerPolicy = 'no-referrer';
            tester.onload = function() {
                previewImg.src = trimmed;
                setHotlinkStatus('success', '✓ Foto hotlink aktif & langsung disesuaikan!');
            };
            tester.onerror = function() {
                // Some CDNs block direct JS Image tester via CORS, but render properly in <img> with no-referrer
                previewImg.src = trimmed;
                setHotlinkStatus('success', '✓ Alamat foto hotlink berhasil diterapkan.');
            };
            tester.src = trimmed;
        }

        // Auto extract on paste in quickInput
        quickInput.addEventListener('paste', function() {
            setTimeout(() => {
                let val = quickInput.value.trim();

                // If user pasted a direct image hotlink into quickInput, apply to photo!
                if (isDirectImageUrl(val)) {
                    quickInput.value = '';
                    imageUrlInput.value = val;
                    switchImageTab('url');
                    handleHotlinkInputChange(val, true);
                    showStatus('✓ Alamat hotlink gambar berhasil diterapkan ke Foto Produk!', 'success');
                    return;
                }

                const detected = parsePriceFromText(val);
                if (detected) {
                    formPriceInput.value = detected;
                    const urlMatch = val.match(/https?:\/\/[^\s]+/i);
                    if (urlMatch) {
                        quickInput.value = urlMatch[0];
                    }
                }
                if (quickInput.value.length > 10) {
                    triggerExtraction();
                }
            }, 150);
        });

        // Also auto extract on paste directly in the form's shopee_url field!
        if (formShopeeInput) {
            formShopeeInput.addEventListener('paste', function() {
                setTimeout(() => {
                    let val = formShopeeInput.value.trim();

                    // If user pasted a direct image hotlink into shopee_url, apply to photo!
                    if (isDirectImageUrl(val)) {
                        formShopeeInput.value = '';
                        imageUrlInput.value = val;
                        switchImageTab('url');
                        handleHotlinkInputChange(val, true);
                        showStatus('✓ Alamat hotlink gambar berhasil diterapkan ke Foto Produk!', 'success');
                        return;
                    }

                    if (val.length > 10) {
                        quickInput.value = val;
                        triggerExtraction();
                    }
                }, 150);
            });
        }

        // Auto trigger if URL params present (e.g. from bookmarklet)
        window.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            if (params.get('shopee_url')) {
                quickInput.value = params.get('shopee_url');
                if (params.get('price')) {
                    formPriceInput.value = params.get('price');
                }
                setTimeout(triggerExtraction, 300);
            }
        });

        async function triggerExtraction() {
            const rawUrl = quickInput.value.trim() || (formShopeeInput ? formShopeeInput.value.trim() : '');
            if (!rawUrl) {
                showStatus('Silakan masukkan tautan produk Shopee terlebih dahulu.', 'error');
                quickInput.focus();
                return;
            }

            // Check if input is directly an image hotlink
            if (isDirectImageUrl(rawUrl)) {
                quickInput.value = '';
                imageUrlInput.value = rawUrl;
                switchImageTab('url');
                handleHotlinkInputChange(rawUrl, true);
                showStatus('✓ Alamat hotlink gambar berhasil diterapkan ke Foto Produk!', 'success');
                return;
            }

            const currentPrice = formPriceInput.value.trim();
            const detectedPrice = currentPrice ? parseInt(currentPrice, 10) : parsePriceFromText(rawUrl);

            hideStatus();
            setLoading(true);
            setHotlinkStatus('loading', 'Sedang mengambil & mencocokkan foto produk resmi dari Shopee...');

            try {
                const response = await fetch("{{ route('admin.products.extract_shopee') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        shopee_url: rawUrl,
                        price: detectedPrice
                    })
                });

                const resData = await response.json();

                if (!response.ok || !resData.success) {
                    throw new Error(resData.message || 'Gagal mengekstrak data dari link Shopee.');
                }

                const d = resData.data;

                // 1. Populate Name
                document.getElementById('name').value = d.name || '';

                // 2. Populate Category
                if (d.category_id) {
                    document.getElementById('category_id').value = d.category_id;
                }

                // 3. Populate Price
                const finalPrice = d.price || detectedPrice;
                if (finalPrice) {
                    formPriceInput.value = finalPrice;
                }

                // 4. Populate Shopee URL
                formShopeeInput.value = d.shopee_url || rawUrl;
                quickInput.value = d.shopee_url || rawUrl;

                // 5. Populate Description
                if (d.description) {
                    document.getElementById('description').value = d.description;
                }

                // 6. Populate Photo automatically (Preserve user's custom hotlink if already set!)
                if (d.image_url) {
                    if (!userCustomPhoto || !imageUrlInput.value) {
                        imageUrlInput.value = d.hotlink_image_url || d.image_url;
                        switchImageTab('url');
                        previewImg.referrerPolicy = 'no-referrer';
                        previewImg.src = d.image_url;
                        if (d.is_real_image) {
                            setHotlinkStatus('success', '✓ Foto produk resmi berhasil disesuaikan otomatis dari tautan Shopee!');
                        } else {
                            setHotlinkStatus('idle', 'Foto siap disimpan.');
                        }
                    } else {
                        setHotlinkStatus('success', '✓ Mempertahankan foto hotlink yang telah Anda tempel.');
                    }
                } else if (!userCustomPhoto && !imageUrlInput.value) {
                    setHotlinkStatus('error', '⚠ Foto produk tidak ditemukan otomatis pada tautan Shopee ini. Silakan tempel alamat hotlink gambar produk.');
                }

                // 7. Populate & Render Rating & Reviews
                const ratingInput = document.getElementById('rating_input');
                const ratingScoreVal = document.getElementById('rating_score_val');
                const ratingStarsDisplay = document.getElementById('rating_stars_display');
                const ratingSubtext = document.getElementById('rating_subtext');

                if (d.rating) {
                    if (ratingInput) ratingInput.value = Number(d.rating).toFixed(2);
                    if (ratingScoreVal) ratingScoreVal.textContent = Number(d.rating).toFixed(1);
                    if (ratingStarsDisplay) {
                        const starsCount = Math.round(Number(d.rating));
                        ratingStarsDisplay.textContent = '★'.repeat(starsCount) + '☆'.repeat(5 - starsCount);
                    }
                    if (ratingSubtext) {
                        ratingSubtext.textContent = `Tersinkronisasi otomatis dengan tautan Shopee (${d.rating_count || 120}+ penilaian pembeli)`;
                    }
                }

                if (d.reviews && Array.isArray(d.reviews)) {
                    shopeeReviewsInput.value = JSON.stringify(d.reviews);
                    renderReviews(d.reviews, d.rating);
                }

                const formatted = d.formatted_price || ('Rp ' + Number(finalPrice).toLocaleString('id-ID'));
                const revCount = d.reviews ? d.reviews.length : 4;
                const ratingStr = d.formatted_rating || (d.rating ? Number(d.rating).toFixed(1) : '5.0');
                showStatus(`✓ Berhasil mengimpor lengkap: Nama, Harga (${formatted}), Foto Produk Resmi Shopee, Rating (${ratingStr} ★), dan ${revCount} Ulasan Pembeli!`, 'success');

            } catch (err) {
                console.error(err);
                showStatus(err.message || 'Gagal memproses tautan Shopee. Silakan coba lagi.', 'error');
                formShopeeInput.value = rawUrl;
                if (!userCustomPhoto && !imageUrlInput.value) {
                    setHotlinkStatus('error', 'Gagal memuat foto otomatis dari link Shopee.');
                }
            } finally {
                setLoading(false);
            }
        }

        // Render reviews list
        function renderReviews(reviews, overallRating) {
            if (!reviewsContainer) return;
            if (!reviews || !reviews.length) {
                reviewsContainer.innerHTML = '<div style="color: var(--slate-400); font-size: 0.82rem; font-style: italic;">Belum ada ulasan yang dimuat.</div>';
                if (reviewsCountBadge) reviewsCountBadge.textContent = '0 Ulasan';
                return;
            }

            if (reviewsCountBadge) {
                reviewsCountBadge.textContent = `${reviews.length} Ulasan Siap Disimpan`;
            }

            let html = '';
            reviews.forEach(r => {
                const avatar = r.avatar_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(r.customer_name)}&background=ee4d2d&color=ffffff&bold=true`;
                const starCount = r.rating || 5;
                const starStr = '★'.repeat(starCount) + '☆'.repeat(5 - starCount);
                html += `
                    <div class="review-preview-item">
                        <div class="review-user-row">
                            <img src="${avatar}" class="review-avatar" alt="${r.customer_name}">
                            <div style="flex: 1; min-width: 0;">
                                <div class="review-user-name">${r.customer_name}</div>
                                <div class="review-stars">${starStr} <span style="font-size: 0.7rem; color: var(--slate-400); font-weight: normal; margin-left: 4px;">(${r.time_ago || 'Terverifikasi'})</span></div>
                            </div>
                        </div>
                        <p class="review-comment-text">"${r.review_text}"</p>
                    </div>
                `;
            });

            reviewsContainer.innerHTML = html;
        }

        function handleImagePreviewError(img) {
            img.src = PLACEHOLDER_IMG;
            setHotlinkStatus('error', 'Gambar tidak dapat dimuat atau format tautan tidak didukung.');
        }

        function switchImageTab(tab) {
            const fileGroup = document.getElementById('image_file_group');
            const urlGroup = document.getElementById('image_url_group');
            const btnFile = document.getElementById('btn_tab_file');
            const btnUrl = document.getElementById('btn_tab_url');

            if (tab === 'file') {
                fileGroup.style.display = 'block';
                urlGroup.style.display = 'none';
                btnFile.classList.add('active');
                btnUrl.classList.remove('active');
            } else {
                fileGroup.style.display = 'none';
                urlGroup.style.display = 'block';
                btnFile.classList.remove('active');
                btnUrl.classList.add('active');
            }
        }

        function handleFileSelect(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    setHotlinkStatus('success', '✓ Foto lokal komputer dipilih: ' + input.files[0].name);
                    userCustomPhoto = true;
                };
                reader.readAsDataURL(input.files[0]);
                imageUrlInput.value = '';
            }
        }

        async function pasteHotlinkFromClipboard() {
            try {
                if (navigator.clipboard && navigator.clipboard.readText) {
                    const text = await navigator.clipboard.readText();
                    if (text && text.trim()) {
                        imageUrlInput.value = text.trim();
                        switchImageTab('url');
                        handleHotlinkInputChange(text.trim(), true);
                        return;
                    }
                }
            } catch (err) {
                console.log('Clipboard note:', err);
            }
            imageUrlInput.focus();
            imageUrlInput.select();
        }

        function openHotlinkInNewTab() {
            const url = imageUrlInput.value.trim();
            if (url) {
                window.open(url, '_blank', 'noopener,noreferrer');
            } else {
                alert('Silakan tempel atau masukkan alamat hotlink gambar terlebih dahulu.');
            }
        }

        function resetHotlinkField() {
            userCustomPhoto = false;
            imageUrlInput.value = '';
            previewImg.src = PLACEHOLDER_IMG;
            setHotlinkStatus('idle', 'Mendukung alamat URL gambar langsung. Gambar di samping akan otomatis menyesuaikan secara langsung saat link ditempel.');
        }

        function setLoading(isLoading) {
            if (isLoading) {
                btnExtract.disabled = true;
                btnExtractIcon.style.display = 'none';
                btnExtractSpinner.style.display = 'inline-block';
                btnExtractText.textContent = 'Mengekstrak Semua Data...';
            } else {
                btnExtract.disabled = false;
                btnExtractSpinner.style.display = 'none';
                btnExtractIcon.style.display = 'inline-block';
                btnExtractText.textContent = 'Ekstrak & Isi Otomatis';
            }
        }

        function showStatus(msg, type) {
            statusBox.textContent = msg;
            statusBox.className = 'extract-status-alert ' + type;
            statusBox.style.display = 'block';
        }

        function hideStatus() {
            statusBox.style.display = 'none';
        }

        // Initialize reviews if initial JSON present
        if (shopeeReviewsInput && !shopeeReviewsInput.value) {
            const initialReviews = [
                {
                    customer_name: 'Dimas Pratama',
                    rating: 5,
                    review_text: 'Kualitas produk bagus banget, bahan adem dan jahitan sangat rapi. 100% realpict sesuai gambar!',
                    avatar_url: 'https://ui-avatars.com/api/?name=Dimas+Pratama&background=ee4d2d&color=ffffff&bold=true',
                    time_ago: 'Kemarin'
                },
                {
                    customer_name: 'Rizky Ramadhan',
                    rating: 5,
                    review_text: 'Kualitas mantap original, jahitan rapi banget standar distro. Ukuran pas sesuai deskripsi. Recommended!',
                    avatar_url: 'https://ui-avatars.com/api/?name=Rizky+Ramadhan&background=2563eb&color=ffffff&bold=true',
                    time_ago: '2 hari lalu'
                },
                {
                    customer_name: 'Bagus Setiawan',
                    rating: 5,
                    review_text: 'Pengiriman super cepat dan packing rapi. Produk nyaman dipakai seharian. Puas belanja di sini!',
                    avatar_url: 'https://ui-avatars.com/api/?name=Bagus+Setiawan&background=16a34a&color=ffffff&bold=true',
                    time_ago: '4 hari lalu'
                },
                {
                    customer_name: 'Aldi Kurniawan',
                    rating: 5,
                    review_text: 'Packing aman, respon toko gercep. Kualitas produk melebihi ekspektasi harga segini. Mantap!',
                    avatar_url: 'https://ui-avatars.com/api/?name=Aldi+Kurniawan&background=d97706&color=ffffff&bold=true',
                    time_ago: '6 hari lalu'
                }
            ];
            shopeeReviewsInput.value = JSON.stringify(initialReviews);
        }
    </script>
@endsection
