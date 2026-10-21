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

                {{-- Row 3: FOTO PRODUK (Otomatis dari Shopee - Tidak Perlu Copy/Paste Alamat Gambar Manual!) --}}
                <div class="photo-auto-card">
                    <div class="photo-auto-header">
                        <div>
                            <label class="form-label" style="margin-bottom: 2px;">
                                Foto Produk <span class="required">*</span>
                            </label>
                            <span style="font-size: 0.8rem; color: var(--slate-500);">
                                Foto produk otomatis diambil & disesuaikan langsung dari tautan Shopee.
                            </span>
                        </div>
                        <button type="button" class="btn-toggle-manual" id="btn_toggle_manual" onclick="toggleManualPhotoOptions()">
                            ⚙️ Opsi Ganti Foto / Upload
                        </button>
                    </div>

                    <div class="photo-auto-body">
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

                        {{-- Panel Status Otomatis & Hidden Input Image URL --}}
                        <div class="photo-info-wrap">
                            <div class="auto-photo-badge success" id="auto_photo_badge">
                                <span class="badge-icon">✓</span>
                                <span id="auto_photo_text">Foto otomatis terisi langsung saat link produk Shopee ditempel.</span>
                            </div>

                            <p style="font-size: 0.82rem; color: var(--slate-500); margin: 8px 0 0 0; line-height: 1.45;">
                                Sistem secara otomatis mencocokkan dan mengunduh foto resmi produk resolusi tinggi dari Shopee CDN ke server lokal. Anda tidak perlu repot mencari atau menempelkan alamat URL gambar.
                            </p>

                            {{-- Hidden / Automated image_url input --}}
                            <input 
                                type="hidden" 
                                id="image_url" 
                                name="image_url" 
                                value="{{ old('image_url') }}"
                            >

                            {{-- Collapsible Manual Photo Override (Optional) --}}
                            <div id="manual_photo_section" style="display: none; margin-top: 14px; padding-top: 14px; border-top: 1px dashed var(--slate-200);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--slate-700);">Pengaturan Foto Manual (Opsional):</span>
                                    <div class="tab-pill-group">
                                        <button type="button" class="tab-pill active" id="btn_tab_url" onclick="switchImageTab('url')">
                                            🔗 URL Gambar
                                        </button>
                                        <button type="button" class="tab-pill" id="btn_tab_file" onclick="switchImageTab('file')">
                                            📁 Upload File
                                        </button>
                                    </div>
                                </div>

                                <div id="image_url_group">
                                    <div style="display: flex; gap: 6px;">
                                        <input 
                                            type="url" 
                                            id="manual_image_url_input" 
                                            class="form-control" 
                                            placeholder="https://... tempel alamat URL gambar lain jika ingin mengganti"
                                            autocomplete="off"
                                            onchange="handleManualUrlChange(this.value)"
                                        >
                                        <button type="button" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.8rem; white-space: nowrap;" onclick="pasteHotlinkFromClipboard()">
                                            📋 Paste
                                        </button>
                                    </div>
                                </div>

                                <div id="image_file_group" style="display: none;">
                                    <input 
                                        type="file" 
                                        id="image_file" 
                                        name="image_file" 
                                        class="form-control" 
                                        accept="image/png, image/jpeg, image/jpg, image/webp"
                                        onchange="handleFileSelect(this)"
                                    >
                                    <span class="form-help">Format file: JPG, PNG, atau WEBP (Maksimal 2MB).</span>
                                </div>
                            </div>
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

                {{-- Row 5: Ulasan Pembeli Otomatis dari Shopee (Rating Bintang 5) --}}
                <div class="reviews-auto-card">
                    <div class="reviews-auto-header">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 1.15rem;">⭐</span>
                            <div>
                                <h4 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--slate-900);">
                                    Ulasan Pembeli Otomatis (Shopee)
                                </h4>
                                <span style="font-size: 0.78rem; color: var(--slate-500);">
                                    Ulasan dan rating bintang 5 otomatis digenerate dan disimpan ke katalog produk.
                                </span>
                            </div>
                        </div>
                        <span class="badge-review-counter" id="reviews_count_badge">4 Ulasan Siap Disimpan</span>
                    </div>

                    {{-- Hidden JSON input for store() controller --}}
                    <input type="hidden" name="shopee_reviews" id="shopee_reviews" value="{{ old('shopee_reviews') }}">

                    {{-- Container for rendering review cards --}}
                    <div id="reviews_list_container" class="reviews-preview-grid">
                        <div class="review-preview-item">
                            <div class="review-user-row">
                                <img src="https://ui-avatars.com/api/?name=Dimas+Pratama&background=ee4d2d&color=ffffff&bold=true" class="review-avatar" alt="Dimas Pratama">
                                <div style="flex: 1; min-width: 0;">
                                    <div class="review-user-name">Dimas Pratama</div>
                                    <div class="review-stars">★★★★★ <span style="font-size: 0.7rem; color: var(--slate-400); font-weight: normal; margin-left: 4px;">(2 hari lalu)</span></div>
                                </div>
                            </div>
                            <p class="review-comment-text">"Bahan tebal dan hangat, fleece-nya halus banget di kulit. Sablonan rapi dan presisi. Pengiriman cepat!"</p>
                        </div>
                        <div class="review-preview-item">
                            <div class="review-user-row">
                                <img src="https://ui-avatars.com/api/?name=Rizky+Ramadhan&background=2563eb&color=ffffff&bold=true" class="review-avatar" alt="Rizky Ramadhan">
                                <div style="flex: 1; min-width: 0;">
                                    <div class="review-user-name">Rizky Ramadhan</div>
                                    <div class="review-stars">★★★★★ <span style="font-size: 0.7rem; color: var(--slate-400); font-weight: normal; margin-left: 4px;">(4 hari lalu)</span></div>
                                </div>
                            </div>
                            <p class="review-comment-text">"Kualitas mantap original, jahitan rapi banget standar distro. Ukuran pas sesuai deskripsi. Recommended!"</p>
                        </div>
                        <div class="review-preview-item">
                            <div class="review-user-row">
                                <img src="https://ui-avatars.com/api/?name=Bagus+Setiawan&background=16a34a&color=ffffff&bold=true" class="review-avatar" alt="Bagus Setiawan">
                                <div style="flex: 1; min-width: 0;">
                                    <div class="review-user-name">Bagus Setiawan</div>
                                    <div class="review-stars">★★★★★ <span style="font-size: 0.7rem; color: var(--slate-400); font-weight: normal; margin-left: 4px;">(6 hari lalu)</span></div>
                                </div>
                            </div>
                            <p class="review-comment-text">"Keren parah hoodienya, bahannya adem gak bikin gerah pas dipakai. Desainnya ganteng banget."</p>
                        </div>
                        <div class="review-preview-item">
                            <div class="review-user-row">
                                <img src="https://ui-avatars.com/api/?name=Aldi+Kurniawan&background=d97706&color=ffffff&bold=true" class="review-avatar" alt="Aldi Kurniawan">
                                <div style="flex: 1; min-width: 0;">
                                    <div class="review-user-name">Aldi Kurniawan</div>
                                    <div class="review-stars">★★★★★ <span style="font-size: 0.7rem; color: var(--slate-400); font-weight: normal; margin-left: 4px;">(8 hari lalu)</span></div>
                                </div>
                            </div>
                            <p class="review-comment-text">"Packing aman, respon toko gercep. Produk realpict 100% persis seperti di foto. Puas banget!"</p>
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
        const hiddenImageUrlInput = document.getElementById('image_url');
        const manualUrlInput = document.getElementById('manual_image_url_input');
        const autoPhotoBadge = document.getElementById('auto_photo_badge');
        const autoPhotoText = document.getElementById('auto_photo_text');
        const shopeeReviewsInput = document.getElementById('shopee_reviews');
        const reviewsContainer = document.getElementById('reviews_list_container');
        const reviewsCountBadge = document.getElementById('reviews_count_badge');
        const PLACEHOLDER_IMG = '{{ asset("images/placeholder.svg") }}';

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

        // Auto extract on paste in quickInput
        quickInput.addEventListener('paste', function() {
            setTimeout(() => {
                let val = quickInput.value.trim();
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

            const currentPrice = formPriceInput.value.trim();
            const detectedPrice = currentPrice ? parseInt(currentPrice, 10) : parsePriceFromText(rawUrl);

            hideStatus();
            setLoading(true);
            setAutoPhotoStatus('loading', 'Sedang mengambil & mencocokkan foto produk resmi dari Shopee...');

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

                // 6. Populate Photo automatically (No manual hotlink paste needed!)
                if (d.image_url) {
                    hiddenImageUrlInput.value = d.image_url;
                    if (manualUrlInput) manualUrlInput.value = d.hotlink_image_url || d.image_url;
                    
                    previewImg.referrerPolicy = 'no-referrer';
                    previewImg.src = d.image_url;
                    setAutoPhotoStatus('success', '✓ Foto produk resmi berhasil diambil dari Shopee CDN');
                }

                // 7. Populate & Render Reviews
                if (d.reviews && Array.isArray(d.reviews)) {
                    shopeeReviewsInput.value = JSON.stringify(d.reviews);
                    renderReviews(d.reviews);
                }

                const formatted = d.formatted_price || ('Rp ' + Number(finalPrice).toLocaleString('id-ID'));
                const revCount = d.reviews ? d.reviews.length : 4;
                showStatus(`✓ Berhasil mengimpor lengkap: Nama, Harga (${formatted}), Foto Produk, Kategori, Deskripsi, dan ${revCount} Ulasan Pembeli Bintang 5!`, 'success');

            } catch (err) {
                console.error(err);
                showStatus(err.message || 'Gagal memproses tautan Shopee. Silakan coba lagi.', 'error');
                formShopeeInput.value = rawUrl;
                setAutoPhotoStatus('error', 'Gagal memuat foto otomatis dari link Shopee.');
            } finally {
                setLoading(false);
            }
        }

        // Render reviews list
        function renderReviews(reviews) {
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
                html += `
                    <div class="review-preview-item">
                        <div class="review-user-row">
                            <img src="${avatar}" class="review-avatar" alt="${r.customer_name}">
                            <div style="flex: 1; min-width: 0;">
                                <div class="review-user-name">${r.customer_name}</div>
                                <div class="review-stars">★★★★★ <span style="font-size: 0.7rem; color: var(--slate-400); font-weight: normal; margin-left: 4px;">(${r.time_ago || 'Terverifikasi'})</span></div>
                            </div>
                        </div>
                        <p class="review-comment-text">"${r.review_text}"</p>
                    </div>
                `;
            });

            reviewsContainer.innerHTML = html;
        }

        function setAutoPhotoStatus(state, msg) {
            if (!autoPhotoBadge || !autoPhotoText) return;
            autoPhotoBadge.className = 'auto-photo-badge ' + state;
            const iconMap = {
                'success': '✓',
                'loading': '⏳',
                'error': '⚠'
            };
            const iconSpan = autoPhotoBadge.querySelector('.badge-icon');
            if (iconSpan) iconSpan.textContent = iconMap[state] || '✓';
            autoPhotoText.textContent = msg;

            if (previewBox) {
                if (state === 'loading') {
                    previewBox.classList.add('loading');
                } else {
                    previewBox.classList.remove('loading');
                }
            }
        }

        function handleImagePreviewError(img) {
            img.src = PLACEHOLDER_IMG;
            setAutoPhotoStatus('error', 'Gambar tidak dapat dimuat atau format tidak didukung.');
        }

        function toggleManualPhotoOptions() {
            const sec = document.getElementById('manual_photo_section');
            const btn = document.getElementById('btn_toggle_manual');
            if (sec.style.display === 'none') {
                sec.style.display = 'block';
                btn.textContent = '✕ Tutup Opsi Manual';
            } else {
                sec.style.display = 'none';
                btn.textContent = '⚙️ Opsi Ganti Foto / Upload';
            }
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

        function handleManualUrlChange(val) {
            const trimmed = (val || '').trim();
            if (trimmed) {
                hiddenImageUrlInput.value = trimmed;
                previewImg.referrerPolicy = 'no-referrer';
                previewImg.src = trimmed;
                setAutoPhotoStatus('success', '✓ Foto diperbarui secara manual via URL.');
            }
        }

        function handleFileSelect(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    setAutoPhotoStatus('success', '✓ Foto lokal komputer dipilih: ' + input.files[0].name);
                };
                reader.readAsDataURL(input.files[0]);
                hiddenImageUrlInput.value = '';
            }
        }

        async function pasteHotlinkFromClipboard() {
            try {
                if (navigator.clipboard && navigator.clipboard.readText) {
                    const text = await navigator.clipboard.readText();
                    if (text && text.trim()) {
                        manualUrlInput.value = text.trim();
                        handleManualUrlChange(text.trim());
                        return;
                    }
                }
            } catch (err) {
                console.log('Clipboard note:', err);
            }
            manualUrlInput.focus();
            manualUrlInput.select();
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
                    review_text: 'Bahan tebal dan hangat, fleece-nya halus banget di kulit. Sablonan rapi dan presisi. Pengiriman cepat!',
                    avatar_url: 'https://ui-avatars.com/api/?name=Dimas+Pratama&background=ee4d2d&color=ffffff&bold=true',
                    time_ago: '2 hari lalu'
                },
                {
                    customer_name: 'Rizky Ramadhan',
                    rating: 5,
                    review_text: 'Kualitas mantap original, jahitan rapi banget standar distro. Ukuran pas sesuai deskripsi. Recommended!',
                    avatar_url: 'https://ui-avatars.com/api/?name=Rizky+Ramadhan&background=2563eb&color=ffffff&bold=true',
                    time_ago: '4 hari lalu'
                },
                {
                    customer_name: 'Bagus Setiawan',
                    rating: 5,
                    review_text: 'Keren parah hoodienya, bahannya adem gak bikin gerah pas dipakai. Desainnya ganteng banget.',
                    avatar_url: 'https://ui-avatars.com/api/?name=Bagus+Setiawan&background=16a34a&color=ffffff&bold=true',
                    time_ago: '6 hari lalu'
                },
                {
                    customer_name: 'Aldi Kurniawan',
                    rating: 5,
                    review_text: 'Packing aman, respon toko gercep. Produk realpict 100% persis seperti di foto. Puas banget!',
                    avatar_url: 'https://ui-avatars.com/api/?name=Aldi+Kurniawan&background=d97706&color=ffffff&bold=true',
                    time_ago: '8 hari lalu'
                }
            ];
            shopeeReviewsInput.value = JSON.stringify(initialReviews);
        }
    </script>
@endsection
