@extends('layouts.admin')

@section('title', 'Tambah Produk Baru - Admin PT Imersa')
@section('header_title', 'Tambah Produk Baru')

@section('content')
    <div style="max-width: 920px; margin: 0 auto;">
        
        {{-- Shopee Quick Importer (Streamlined) --}}
        <div class="shopee-quick-card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div class="shopee-icon-badge">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19.38 6.64H16.5a4.5 4.5 0 0 0-9 0H4.62A1.63 1.63 0 0 0 3 8.27l1.37 12.35A2.45 2.45 0 0 0 6.82 23h10.36a2.45 2.45 0 0 0 2.45-2.38L21 8.27a1.63 1.63 0 0 0-1.62-1.63zM12 3.75a2.88 2.88 0 0 1 2.87 2.89H9.13A2.88 2.88 0 0 1 12 3.75zm6 16.75a.82.82 0 0 1-.82.78H6.82a.82.82 0 0 1-.82-.78L4.69 8.27H19.3z"/>
                        </svg>
                    </div>
                    <div>
                        <span style="font-size: 0.95rem; font-weight: 700; color: var(--slate-900);">Impor Otomatis dari Shopee</span>
                        <span style="font-size: 0.72rem; font-weight: 600; background: var(--slate-100); color: var(--slate-600); padding: 2px 8px; border-radius: var(--radius-full); margin-left: 6px;">Opsional</span>
                    </div>
                </div>
            </div>

            <p style="font-size: 0.84rem; color: var(--slate-500); margin: 0 0 12px 0; line-height: 1.45;">
                Tempelkan tautan produk Shopee atau teks <em>Share</em> untuk mengisi otomatis nama, foto/hotlink, harga, kategori, dan deskripsi produk.
            </p>

            <div class="shopee-input-bar">
                <input 
                    type="text" 
                    id="quick_shopee_url" 
                    class="form-control" 
                    placeholder="Tempel link produk Shopee (misal: https://shopee.co.id/product/...)"
                    value="{{ request('shopee_url', old('shopee_url')) }}"
                    autocomplete="off"
                >
                <button type="button" id="btn_extract" class="btn-extract-action" onclick="triggerExtraction()">
                    <span id="btn_extract_spinner" class="spinner-icon" style="display: none;"></span>
                    <span id="btn_extract_icon">⚡</span>
                    <span id="btn_extract_text">Ekstrak & Isi Form</span>
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
                    <p style="font-size: 0.85rem; color: var(--slate-500); margin: 0;">Sesuaikan foto dengan alamat hotlink gambar dan deskripsi produk secara presisi.</p>
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

                {{-- Row 3: FOTO PRODUK & ALAMAT HOTLINK (Bersanding berdampingan secara langsung!) --}}
                <div class="photo-hotlink-card">
                    <div class="photo-hotlink-header">
                        <div>
                            <label class="form-label" style="margin-bottom: 2px;">
                                Foto Produk & Alamat Hotlink <span class="required">*</span>
                            </label>
                            <span style="font-size: 0.8rem; color: var(--slate-500);">
                                Pratinjau foto langsung menyesuaikan dengan alamat hotlink URL gambar yang dimasukkan.
                            </span>
                        </div>
                        <div class="tab-pill-group" style="margin-bottom: 0;">
                            <button type="button" class="tab-pill active" id="btn_tab_url" onclick="switchImageTab('url')">
                                🔗 Alamat Hotlink (URL)
                            </button>
                            <button type="button" class="tab-pill" id="btn_tab_file" onclick="switchImageTab('file')">
                                📁 Upload File
                            </button>
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
                                    onerror="handleImagePreviewError(this)"
                                >
                                <div class="preview-spinner" id="preview_spinner"></div>
                            </div>
                            <span class="photo-preview-tag" id="preview_tag">Pratinjau Gambar</span>
                        </div>

                        {{-- Input Alamat Hotlink / File --}}
                        <div class="photo-input-wrap">
                            <div id="image_url_group">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px; flex-wrap: wrap; gap: 6px;">
                                    <label for="image_url" style="font-size: 0.82rem; font-weight: 700; color: var(--slate-700); margin: 0;">
                                        Alamat Hotlink Gambar (URL Foto):
                                    </label>
                                    <div style="display: flex; align-items: center; gap: 4px;">
                                        <button type="button" class="btn-hotlink-tool" onclick="pasteHotlinkFromClipboard()" title="Tempel langsung dari Clipboard">
                                            📋 Tempel (Paste)
                                        </button>
                                        <button type="button" class="btn-hotlink-tool" onclick="openHotlinkInNewTab()" title="Buka tautan gambar di tab baru">
                                            🔗 Buka Link
                                        </button>
                                        <button type="button" class="btn-hotlink-tool" onclick="resetHotlinkField()" title="Hapus URL dan kembalikan pratinjau">
                                            ✕ Reset
                                        </button>
                                    </div>
                                </div>
                                <input 
                                    type="url" 
                                    id="image_url" 
                                    name="image_url" 
                                    value="{{ old('image_url') }}" 
                                    class="form-control" 
                                    placeholder="https://... tempel alamat hotlink gambar di sini (CDN Shopee, Unsplash, dsb.)"
                                    autocomplete="off"
                                >
                                <div id="hotlink_status" class="hotlink-status-badge idle">
                                    <span class="badge-icon">ℹ️</span>
                                    <span id="hotlink_status_text">Mendukung tautan gambar langsung dari web/CDN. Pratinjau di samping akan otomatis menyesuaikan secara langsung saat link ditempel.</span>
                                </div>
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
                                <span class="form-help">Format file: JPG, PNG, atau WEBP (Maksimal 2MB).</span>
                            </div>
                        </div>
                    </div>

                    @error('image_url')
                        <div class="form-error" style="margin-top: 8px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Row 4: Deskripsi Produk (Tepat di bawah Foto & Alamat Hotlink!) --}}
                <div class="form-group" style="margin-top: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="description" class="form-label" style="margin-bottom: 0;">
                            Deskripsi Lengkap Produk <span class="required">*</span>
                        </label>
                        <span style="font-size: 0.78rem; color: var(--slate-400);">
                            Sesuaikan rincian bahan, ukuran, dan spesifikasi dengan foto produk di atas
                        </span>
                    </div>
                    <textarea 
                        id="description" 
                        name="description" 
                        class="form-control" 
                        rows="7" 
                        required
                        placeholder="Tuliskan spesifikasi bahan pakaian (misal: 100% Cotton Combed 24s), fitting (boxy/regular), ukuran, dan keunggulan produk yang sesuai dengan foto dan spesifikasi aslinya..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Row 5: Produk Unggulan --}}
                <div style="margin-top: 16px;">
                    <label class="custom-checkbox-label">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <div>
                            <span class="checkbox-title">Jadikan sebagai Produk Unggulan</span>
                            <span class="checkbox-desc">Tampilkan produk ini di barisan terdepan pada beranda katalog toko.</span>
                        </div>
                    </label>
                </div>

                {{-- Row 6: Submit Buttons --}}
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

        /* Paired Photo & Hotlink Card */
        .photo-hotlink-card {
            background-color: var(--slate-50);
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-lg);
            padding: 18px 20px;
            margin-top: 8px;
        }

        .photo-hotlink-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 14px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .photo-hotlink-body {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 20px;
            align-items: start;
        }

        @media (max-width: 640px) {
            .photo-hotlink-body {
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
            border: 3px solid rgba(224, 86, 36, 0.2);
            border-top-color: var(--primary);
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

        .photo-input-wrap {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 140px;
        }

        .btn-hotlink-tool {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--slate-600);
            background: var(--slate-100);
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
            line-height: 1.2;
        }

        .btn-hotlink-tool:hover {
            background: var(--slate-200);
            color: var(--slate-900);
        }

        .hotlink-status-badge {
            margin-top: 6px;
            font-size: 0.78rem;
            line-height: 1.4;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: var(--radius-sm);
            transition: all 0.2s ease;
        }

        .hotlink-status-badge.idle {
            background: transparent;
            color: var(--slate-500);
            padding: 2px 0;
            border: none;
        }

        .hotlink-status-badge.loading {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            font-weight: 600;
        }

        .hotlink-status-badge.success {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
            font-weight: 600;
        }

        .hotlink-status-badge.error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            font-weight: 600;
        }

        .hotlink-status-badge .badge-icon {
            font-size: 0.85rem;
            display: inline-flex;
            flex-shrink: 0;
        }

        /* Pill Tabs */
        .tab-pill-group {
            display: inline-flex;
            gap: 4px;
            background: var(--slate-200);
            padding: 3px;
            border-radius: var(--radius-md);
        }

        .tab-pill {
            padding: 5px 12px;
            font-size: 0.8rem;
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
        const urlInput = document.getElementById('image_url');

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

        // Auto extract on paste
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
            const rawUrl = quickInput.value.trim();
            if (!rawUrl) {
                showStatus('Silakan masukkan tautan produk Shopee terlebih dahulu.', 'error');
                quickInput.focus();
                return;
            }

            const currentPrice = formPriceInput.value.trim();
            const detectedPrice = currentPrice ? parseInt(currentPrice, 10) : parsePriceFromText(rawUrl);

            hideStatus();
            setLoading(true);

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

                // Populate form fields
                formShopeeInput.value = d.shopee_url || rawUrl;
                document.getElementById('name').value = d.name || '';
                
                if (d.category_id) {
                    document.getElementById('category_id').value = d.category_id;
                }
                
                const finalPrice = d.price || detectedPrice;
                if (finalPrice) {
                    formPriceInput.value = finalPrice;
                }

                // Populate hotlink image url and update preview
                const hotlinkUrl = d.hotlink_image_url || d.image_url;
                if (hotlinkUrl) {
                    urlInput.value = hotlinkUrl;
                    updatePreviewImage(hotlinkUrl);
                    switchImageTab('url');
                }

                // Populate description
                if (d.description) {
                    document.getElementById('description').value = d.description;
                }

                if (finalPrice) {
                    const formatted = d.formatted_price || ('Rp ' + Number(finalPrice).toLocaleString('id-ID'));
                    showStatus('✓ Berhasil mengimpor data produk dari Shopee! Harga terdeteksi: ' + formatted + '.', 'success');
                } else {
                    showStatus('✓ Foto dan rincian produk berhasil diimpor. Silakan konfirmasi harga produk pada kolom harga.', 'warning');
                    formPriceInput.focus();
                    formPriceInput.classList.add('price-highlight');
                    setTimeout(() => formPriceInput.classList.remove('price-highlight'), 3000);
                }
            } catch (err) {
                console.error(err);
                showStatus(err.message || 'Gagal memproses tautan Shopee. Silakan coba lagi.', 'error');
                formShopeeInput.value = rawUrl;
            } finally {
                setLoading(false);
            }
        }

        // --- Hotlink Image Management & Reactive Preview ---
        const previewBox = document.getElementById('preview_box');
        const previewSpinner = document.getElementById('preview_spinner');
        const hotlinkStatus = document.getElementById('hotlink_status');
        const hotlinkStatusText = document.getElementById('hotlink_status_text');
        const PLACEHOLDER_IMG = '{{ asset("images/placeholder.svg") }}';

        function cleanImageUrl(raw) {
            if (!raw) return '';
            let url = String(raw).trim();
            // Remove wrapping quotes, backticks, or smart quotes
            url = url.replace(/^["'“`]+|["'”`]+$/g, '').trim();
            // If user pasted an HTML img tag e.g. <img src="https://...">
            const imgMatch = url.match(/src=["']([^"']+)["']/i);
            if (imgMatch) url = imgMatch[1].trim();
            // If user pasted markdown image syntax ![alt](https://...)
            const mdMatch = url.match(/\((https?:\/\/[^\s)]+)\)/i);
            if (mdMatch) url = mdMatch[1].trim();
            // Handle protocol-relative //
            if (url.startsWith('//')) {
                url = 'https:' + url;
            } else if (!/^https?:\/\//i.test(url) && !url.startsWith('data:image/')) {
                if (/^[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+\//.test(url)) {
                    url = 'https://' + url;
                }
            }
            return url;
        }

        function setHotlinkStatus(state, msg) {
            if (!hotlinkStatus || !hotlinkStatusText) return;
            hotlinkStatus.className = 'hotlink-status-badge ' + state;
            const iconMap = {
                'idle': 'ℹ️',
                'loading': '⏳',
                'success': '✓',
                'error': '⚠'
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

        function isShopeeProductPageUrl(url) {
            if (!url) return false;
            return /shopee\.co\.id|shp\.ee|shope\.ee/i.test(url) && !url.includes('.susercontent.com') && !url.includes('cf.shopee.co.id');
        }

        let hotlinkDebounce = null;
        function updatePreviewImage(param = false) {
            if (typeof param === 'string') {
                if (urlInput) urlInput.value = param;
                applyHotlinkPreview();
                return;
            }
            if (hotlinkDebounce) clearTimeout(hotlinkDebounce);
            if (param === true) {
                applyHotlinkPreview();
            } else {
                hotlinkDebounce = setTimeout(applyHotlinkPreview, 100);
            }
        }

        async function applyHotlinkPreview() {
            if (!urlInput) return;
            const raw = urlInput.value;
            const cleaned = cleanImageUrl(raw);

            // Auto sanitize input if formatting needed
            if (cleaned && cleaned !== raw && !raw.endsWith(' ') && !raw.endsWith('.')) {
                urlInput.value = cleaned;
            }

            if (!cleaned) {
                previewImg.src = PLACEHOLDER_IMG;
                setHotlinkStatus('idle', 'Mendukung tautan gambar langsung dari web/CDN. Pratinjau di samping akan otomatis menyesuaikan secara langsung saat link ditempel.');
                return;
            }

            // If user accidentally pasted a Shopee product link directly into the hotlink input
            if (isShopeeProductPageUrl(cleaned)) {
                setHotlinkStatus('loading', 'Mendeteksi link Shopee... Sedang mengambil foto produk resmi dari Shopee...');
                try {
                    const csrfToken = '{{ csrf_token() }}';
                    const res = await fetch("{{ route('admin.products.extract_shopee') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ shopee_url: cleaned })
                    });
                    const data = await res.json();
                    if (data.success && (data.data.hotlink_image_url || data.data.image_url)) {
                        const imgUrl = data.data.hotlink_image_url || data.data.image_url;
                        urlInput.value = imgUrl;
                        previewImg.src = imgUrl;
                        setHotlinkStatus('success', '✓ Foto produk resmi berhasil diekstrak otomatis dari link Shopee!');
                        
                        // Also populate other fields if currently empty
                        if (formShopeeInput && !formShopeeInput.value) formShopeeInput.value = cleaned;
                        if (formPriceInput && !formPriceInput.value && data.data.price) formPriceInput.value = data.data.price;
                        const nameInput = document.getElementById('name');
                        if (nameInput && !nameInput.value && data.data.name) nameInput.value = data.data.name;
                        return;
                    }
                } catch (e) {
                    console.warn('Shopee auto-extract fallback:', e);
                }
            }

            // Validate and load direct image hotlink
            setHotlinkStatus('loading', 'Memuat pratinjau gambar hotlink...');
            
            const tester = new Image();
            tester.onload = function() {
                previewImg.src = cleaned;
                setHotlinkStatus('success', '✓ Gambar hotlink berhasil dimuat & sesuai!');
            };
            tester.onerror = function() {
                previewImg.src = PLACEHOLDER_IMG;
                setHotlinkStatus('error', '⚠ Gambar tidak dapat dimuat. Pastikan URL berupa tautan gambar langsung (.jpg, .png, .webp, dsb.) dan dapat diakses publik.');
            };
            tester.src = cleaned;
        }

        function handleImagePreviewError(img) {
            img.src = PLACEHOLDER_IMG;
            setHotlinkStatus('error', '⚠ Gambar tidak dapat dimuat atau tautan rusak.');
        }

        async function pasteHotlinkFromClipboard() {
            try {
                if (navigator.clipboard && navigator.clipboard.readText) {
                    const text = await navigator.clipboard.readText();
                    if (text && text.trim()) {
                        urlInput.value = text.trim();
                        switchImageTab('url');
                        updatePreviewImage(true);
                        return;
                    }
                }
            } catch (err) {
                console.log('Clipboard read note:', err);
            }
            urlInput.focus();
            urlInput.select();
        }

        function openHotlinkInNewTab() {
            const url = cleanImageUrl(urlInput.value);
            if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
                window.open(url, '_blank');
            } else {
                alert('Silakan masukkan alamat tautan gambar yang valid terlebih dahulu.');
            }
        }

        function resetHotlinkField() {
            urlInput.value = '';
            previewImg.src = PLACEHOLDER_IMG;
            setHotlinkStatus('idle', 'Mendukung tautan gambar langsung dari web/CDN. Pratinjau di samping akan otomatis menyesuaikan secara langsung saat link ditempel.');
            urlInput.focus();
        }

        // Attach listeners for 100% reactive copy-paste (keyboard, mouse right-click, autofill, drag-drop)
        if (urlInput) {
            ['input', 'change', 'blur', 'keyup'].forEach(evtName => {
                urlInput.addEventListener(evtName, () => updatePreviewImage(false));
            });
            urlInput.addEventListener('paste', () => {
                setTimeout(() => updatePreviewImage(true), 15);
            });
            urlInput.addEventListener('drop', () => {
                setTimeout(() => updatePreviewImage(true), 15);
            });
        }

        // Initial preview validation if input already has a value
        if (urlInput && urlInput.value.trim()) {
            updatePreviewImage(true);
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
                };
                reader.readAsDataURL(input.files[0]);
                urlInput.value = '';
            }
        }

        function setLoading(isLoading) {
            if (isLoading) {
                btnExtract.disabled = true;
                btnExtractIcon.style.display = 'none';
                btnExtractSpinner.style.display = 'inline-block';
                btnExtractText.textContent = 'Mengekstrak...';
            } else {
                btnExtract.disabled = false;
                btnExtractSpinner.style.display = 'none';
                btnExtractIcon.style.display = 'inline-block';
                btnExtractText.textContent = 'Ekstrak & Isi Form';
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
    </script>
@endsection
