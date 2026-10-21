@extends('layouts.admin')

@section('title', 'Tambah Produk Baru - Admin PT Imersa')
@section('header_title', 'Tambah Produk Baru')

@section('content')
    <div style="max-width: 1040px; margin: 0 auto;">
        
        {{-- Page Header --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
            <div>
                <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--slate-900); margin: 0 0 4px 0;">
                    Tambah Produk Baru
                </h2>
                <p style="font-size: 0.875rem; color: var(--slate-500); margin: 0;">
                    Isi informasi produk secara manual atau gunakan impor otomatis dari Shopee.
                </p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline" style="padding: 8px 16px; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 6px;">
                &larr; Kembali ke Daftar
            </a>
        </div>

        {{-- Shopee Quick Importer (Clean & Streamlined) --}}
        <div class="shopee-quick-card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
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

            <p style="font-size: 0.84rem; color: var(--slate-500); margin: 0 0 14px 0; line-height: 1.45;">
                Tempelkan link produk Shopee atau teks <em>Share / Salin Tautan</em> untuk mengisi nama, foto, kategori, harga, dan deskripsi secara otomatis.
            </p>

            <div class="shopee-input-bar">
                <input 
                    type="text" 
                    id="quick_shopee_url" 
                    class="form-control" 
                    placeholder="Tempel link Shopee (misal: https://shopee.co.id/product/...)"
                    value="{{ request('shopee_url', old('shopee_url')) }}"
                    autocomplete="off"
                >
                <button type="button" id="btn_extract" class="btn-extract-action" onclick="triggerExtraction()">
                    <span id="btn_extract_spinner" class="spinner-icon" style="display: none;"></span>
                    <span id="btn_extract_icon">⚡</span>
                    <span id="btn_extract_text">Ekstrak & Isi Form</span>
                </button>
            </div>

            {{-- Status Alert (Hidden by default) --}}
            <div id="extract_status" class="extract-status-alert" style="display: none;"></div>

            {{-- Bookmarklet Accordion (Discreet & Non-Intrusive) --}}
            <details class="bookmarklet-tip">
                <summary>
                    💡 <span>Tips: Tarik harga promo otomatis menggunakan Bookmarklet</span>
                </summary>
                <div class="bookmarklet-content">
                    <span>Tarik tombol ini ke <strong>Bookmarks Bar</strong> browser Anda:</span>
                    <a href="javascript:(function(){let p='';let el=document.querySelector('.pqTWkA, ._2Shl1j, .items-center .text-brand-primary, [class*=\'price\'], .G27ShH');if(!el){let all=document.querySelectorAll('div,span');for(let e of all){if(/^Rp\s*[0-9.,]+$/i.test(e.textContent.trim())){el=e;break;}}}if(el){let m=el.textContent.match(/([0-9.,]+)/);if(m)p=m[1].replace(/[^0-9]/g,'');}window.open('{{ url('/admin/products/create') }}?shopee_url='+encodeURIComponent(window.location.href)+(p?'&price='+p:''),'_blank');})();" 
                       class="btn-bookmarklet-chip"
                       onclick="alert('Tarik tombol ini ke Bookmarks Bar browser Anda (tekan Ctrl+Shift+B jika belum muncul). Saat membuka produk di Shopee, klik bookmark untuk auto-import!'); return false;"
                       title="Tarik tombol ini ke Bookmarks Bar browser">
                        🛒 Tarik ke Bookmarks: Impor ke Imersa
                    </a>
                </div>
            </details>
        </div>

        {{-- Product Form --}}
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="product_form">
            @csrf

            <div class="product-create-grid">
                
                {{-- Left Column: Primary Details --}}
                <div class="product-create-main">
                    <div class="admin-card">
                        <div class="admin-card-header" style="border-bottom: 1px solid var(--slate-100); padding-bottom: 14px; margin-bottom: 18px;">
                            <h3 class="admin-card-title" style="font-size: 1.05rem;">Informasi Utama Produk</h3>
                        </div>

                        {{-- Nama Produk --}}
                        <div class="form-group">
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
                                placeholder="Contoh: Kaos Oversize Cotton Combed 24s Premium"
                            >
                            @error('name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tautan Shopee --}}
                        <div class="form-group">
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
                            <span class="form-help">Pengunjung katalog akan diarahkan ke tautan ini ketika menekan tombol "Order di Shopee".</span>
                            @error('shopee_url')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Deskripsi Produk --}}
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="description" class="form-label">
                                Deskripsi Produk <span class="required">*</span>
                            </label>
                            <textarea 
                                id="description" 
                                name="description" 
                                class="form-control" 
                                rows="8" 
                                required
                                placeholder="Tuliskan spesifikasi bahan pakaian, panduan ukuran fitting, keunggulan, dan detail lainnya..."
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Right Column: Media, Categorization, Pricing & Action --}}
                <div class="product-create-sidebar">
                    
                    {{-- Media Card --}}
                    <div class="admin-card">
                        <div class="admin-card-header" style="border-bottom: 1px solid var(--slate-100); padding-bottom: 12px; margin-bottom: 16px;">
                            <h3 class="admin-card-title" style="font-size: 1rem;">Foto Produk</h3>
                        </div>

                        {{-- Image Preview Container --}}
                        <div class="photo-preview-box">
                            <img 
                                id="image_url_preview" 
                                src="{{ old('image_url') ?? asset('images/placeholder.svg') }}" 
                                alt="Pratinjau Foto"
                                onerror="handleImageError(this)"
                            >
                        </div>

                        {{-- Photo Option Switcher --}}
                        <div class="tab-pill-group">
                            <button type="button" class="tab-pill active" id="btn_tab_url" onclick="switchImageTab('url')">
                                🔗 URL Foto
                            </button>
                            <button type="button" class="tab-pill" id="btn_tab_file" onclick="switchImageTab('file')">
                                📁 Upload File
                            </button>
                        </div>

                        {{-- URL Input --}}
                        <div id="image_url_group">
                            <input 
                                type="url" 
                                id="image_url" 
                                name="image_url" 
                                value="{{ old('image_url') }}" 
                                class="form-control" 
                                placeholder="https://... alamat gambar Shopee/CDN"
                                oninput="updatePreviewImage(this.value)"
                            >
                            <span class="form-help">Foto otomatis terisi dari link Shopee.</span>
                        </div>

                        {{-- File Input --}}
                        <div id="image_file_group" style="display: none;">
                            <input 
                                type="file" 
                                id="image_file" 
                                name="image_file" 
                                class="form-control" 
                                accept="image/png, image/jpeg, image/jpg, image/webp"
                                onchange="handleFileSelect(this)"
                            >
                            <span class="form-help">Format didukung: JPG, PNG, atau WEBP.</span>
                        </div>

                        @error('image_url')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Category & Pricing Card --}}
                    <div class="admin-card">
                        <div class="admin-card-header" style="border-bottom: 1px solid var(--slate-100); padding-bottom: 12px; margin-bottom: 16px;">
                            <h3 class="admin-card-title" style="font-size: 1rem;">Kategori & Harga</h3>
                        </div>

                        {{-- Kategori --}}
                        <div class="form-group">
                            <label for="category_id" class="form-label">Kategori <span class="required">*</span></label>
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

                        {{-- Harga --}}
                        <div class="form-group">
                            <label for="price" class="form-label">Harga (Rupiah) <span class="required">*</span></label>
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

                        {{-- Featured / Unggulan --}}
                        <div style="padding-top: 6px;">
                            <label class="custom-checkbox-label">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <div>
                                    <span class="checkbox-title">Produk Unggulan</span>
                                    <span class="checkbox-desc">Tampilkan di barisan terdepan beranda katalog.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Publish Action Card --}}
                    <div class="admin-card action-card">
                        <button type="submit" class="btn btn-primary btn-save-product">
                            ✓ Simpan & Publikasikan
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-cancel-product">
                            Batal
                        </a>
                    </div>

                </div>

            </div>
        </form>

    </div>

    <style>
        /* Modern 2-Column Product Layout */
        .product-create-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 960px) {
            .product-create-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Shopee Quick Importer Card */
        .shopee-quick-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-lg);
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
            position: relative;
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

        /* Discreet Bookmarklet Helper */
        .bookmarklet-tip {
            margin-top: 14px;
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

        /* Photo Preview Box */
        .photo-preview-box {
            width: 100%;
            aspect-ratio: 1/1;
            max-height: 240px;
            border-radius: var(--radius-md);
            background: #f8fafc;
            border: 1px solid var(--slate-200);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 14px;
        }

        .photo-preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Pill Tabs */
        .tab-pill-group {
            display: flex;
            gap: 6px;
            margin-bottom: 10px;
            background: var(--slate-100);
            padding: 3px;
            border-radius: var(--radius-md);
        }

        .tab-pill {
            flex: 1;
            padding: 6px 10px;
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

        /* Price Input with Prefix */
        .price-input-wrapper {
            position: relative;
        }

        .price-prefix {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 700;
            color: var(--slate-400);
            font-size: 0.9rem;
            pointer-events: none;
        }

        .price-input {
            padding-left: 38px;
            font-weight: 700;
            font-size: 0.95rem;
        }

        /* Custom Checkbox Label */
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

        /* Action Card */
        .action-card {
            padding: 16px;
            background: #fafbfd;
            border: 1px solid var(--slate-200);
        }

        .btn-save-product {
            width: 100%;
            padding: 11px 16px;
            font-weight: 700;
            font-size: 0.92rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-cancel-product {
            width: 100%;
            text-align: center;
            margin-top: 8px;
            font-size: 0.875rem;
            padding: 9px 16px;
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

                if (d.image_url) {
                    urlInput.value = d.image_url;
                    updatePreviewImage(d.image_url);
                }

                if (d.description) {
                    document.getElementById('description').value = d.description;
                }

                if (finalPrice) {
                    const formatted = d.formatted_price || ('Rp ' + Number(finalPrice).toLocaleString('id-ID'));
                    showStatus('✓ Berhasil mengimpor data produk dari Shopee! Harga terdeteksi: ' + formatted + '.', 'success');
                } else {
                    showStatus('✓ Foto dan rincian produk berhasil diimpor. Silakan isi harga produk pada kolom harga di samping.', 'warning');
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

        function updatePreviewImage(url) {
            if (url && url.startsWith('http')) {
                previewImg.src = url;
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

        function handleFileSelect(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
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
