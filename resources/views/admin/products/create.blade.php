@extends('layouts.admin')

@section('title', 'Tambah Produk Baru - Admin PT Imersa')
@section('header_title', 'Tambah Produk Baru')

@section('content')
    <div style="max-width: 860px; margin: 0 auto;">
        
        {{-- Hero Shopee Quick Extractor Bar --}}
        <div class="shopee-importer-hero" style="margin-bottom: 24px;">
            <div class="shopee-badge-header">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19.38 6.64H16.5a4.5 4.5 0 0 0-9 0H4.62A1.63 1.63 0 0 0 3 8.27l1.37 12.35A2.45 2.45 0 0 0 6.82 23h10.36a2.45 2.45 0 0 0 2.45-2.38L21 8.27a1.63 1.63 0 0 0-1.62-1.63zM12 3.75a2.88 2.88 0 0 1 2.87 2.89H9.13A2.88 2.88 0 0 1 12 3.75zm6 16.75a.82.82 0 0 1-.82.78H6.82a.82.82 0 0 1-.82-.78L4.69 8.27H19.3z"/>
                </svg>
                <span>Shopee Auto-Fill Link Helper</span>
            </div>

            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--slate-900); margin-bottom: 6px;">
                ⚡ 1-Click Auto-Import dari Shopee
            </h3>
            <p style="font-size: 0.85rem; color: var(--slate-500); line-height: 1.5; margin-bottom: 8px;">
                Tempelkan tautan produk Shopee atau teks <em>Share / Salin Tautan</em>. Sistem akan <strong>otomatis mengunduh Foto Asli Produk dari Shopee, mengisi Nama Produk, Kategori, Harga, dan Deskripsi</strong> secara presisi.
            </p>
            {{-- Solusi Otomatis 1-Klik via Bookmarklet --}}
            <div style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border: 1.5px dashed #f97316; padding: 16px 20px; border-radius: 10px; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 8px;">
                    <div>
                        <h4 style="margin: 0; color: #9a3412; font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 6px;">
                            🚀 Tombol Pintas 1-Klik: Tarik Harga Asli Langsung dari Shopee
                        </h4>
                        <p style="margin: 4px 0 0 0; font-size: 0.82rem; color: #7c2d12;">
                            Tarik (drag & drop) tombol oranye di bawah ini ke <strong>Bookmarks Bar</strong> browser Anda. Saat membuka produk apa saja di Shopee, cukup klik tombol tersebut dan harga asli (promo/diskon) akan <strong>100% otomatis terbawa</strong> tanpa ketik manual!
                        </p>
                    </div>
                    <div>
                        <a href="javascript:(function(){let p='';let el=document.querySelector('.pqTWkA, ._2Shl1j, .items-center .text-brand-primary, [class*=\'price\'], .G27ShH');if(!el){let all=document.querySelectorAll('div,span');for(let e of all){if(/^Rp\s*[0-9.,]+$/i.test(e.textContent.trim())){el=e;break;}}}if(el){let m=el.textContent.match(/([0-9.,]+)/);if(m)p=m[1].replace(/[^0-9]/g,'');}window.open('http://127.0.0.1:8000/admin/products/create?shopee_url='+encodeURIComponent(window.location.href)+(p?'&price='+p:''),'_blank');})();" 
                           class="btn-shopee-bookmarklet" 
                           style="background: #ee4d2d; color: #ffffff; padding: 10px 18px; border-radius: 8px; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(238, 77, 45, 0.35); cursor: grab; font-size: 0.9rem;"
                           onclick="alert('Tarik tombol ini ke Bookmarks Bar browser Anda (tekan Ctrl+Shift+B jika Bookmarks Bar belum muncul). Lalu buka halaman produk Shopee dan klik bookmark ini untuk auto-import!'); return false;"
                           title="Tarik tombol ini ke Bookmarks Bar browser Anda">
                            <span>🛒 + Tarik ke Bookmarks: Auto-Import Shopee</span>
                        </a>
                    </div>
                </div>
            </div>

            <div style="background: rgba(14, 165, 233, 0.06); border-left: 3px solid var(--primary); padding: 12px 16px; border-radius: 6px; font-size: 0.84rem; color: #0369a1; margin-bottom: 16px; line-height: 1.5;">
                <strong>⚡ Impor Otomatis dari Shopee:</strong> Tempelkan link produk Shopee atau teks Share/Salin Tautan dari Shopee. Jika link belum memuat nominal harga promo, Anda juga dapat menuliskan harga produk di kolom harga sebelah kanan link.
            </div>

            <div class="shopee-input-wrap" style="display: flex; gap: 8px; flex-wrap: wrap;">
                <input 
                    type="text" 
                    id="quick_shopee_url" 
                    class="shopee-url-input" 
                    style="flex: 2; min-width: 280px;"
                    placeholder="Tempel tautan Shopee / teks Salin Tautan di sini..."
                    value="{{ request('shopee_url', old('shopee_url')) }}"
                    autocomplete="off"
                >
                <div style="position: relative; flex: 1; min-width: 160px; max-width: 220px;">
                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-weight: 700; color: var(--slate-400); font-size: 0.85rem; pointer-events: none;">Rp</span>
                    <input 
                        type="number" 
                        id="quick_price" 
                        class="form-control" 
                        style="padding-left: 38px; height: 100%; border-radius: var(--radius-md); font-weight: 700; font-size: 0.9rem;"
                        placeholder="Harga (opsional)"
                        value="{{ request('price') }}"
                        min="0"
                        step="1000"
                    >
                </div>
                <button type="button" id="btn_extract" class="btn-shopee-extract" onclick="triggerExtraction()" style="white-space: nowrap;">
                    <span id="btn_extract_icon">⚡</span>
                    <span id="btn_extract_text">Ekstrak & Isi Otomatis</span>
                </button>
            </div>

            <div id="extract_loading" class="extract-loading-box">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="animation: spin 1s linear infinite;">
                    <line x1="12" y1="2" x2="12" y2="6"></line>
                    <line x1="12" y1="18" x2="12" y2="22"></line>
                    <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                    <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                    <line x1="2" y1="12" x2="6" y2="12"></line>
                    <line x1="18" y1="12" x2="22" y2="12"></line>
                    <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                    <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                </svg>
                <span>Mengekstrak data dari tautan Shopee...</span>
            </div>

            <div id="extract_status" style="display: none; margin-top: 12px; padding: 10px 14px; border-radius: var(--radius-md); font-size: 0.85rem; font-weight: 600;">
            </div>
        </div>

        {{-- Form Produk Lengkap --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h3 class="admin-card-title">Detail Informasi Produk</h3>
                    <p style="font-size: 0.85rem; color: var(--slate-500);">Pastikan nama, harga, dan foto produk sesuai dengan barang asli di Shopee.</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.8rem;">
                    &larr; Kembali
                </a>
            </div>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="product_form">
                @csrf

                {{-- Tautan Shopee Resmi --}}
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="shopee_url" class="form-label" style="margin-bottom: 0;">
                            Tautan Langsung Produk di Shopee <span class="required">*</span>
                        </label>
                        <span style="font-size: 0.75rem; color: var(--emerald-500); font-weight: 700;">
                            ✓ Tautan Langsung ke Barang (Tanpa Error Sesi)
                        </span>
                    </div>
                    <input 
                        type="url" 
                        id="shopee_url" 
                        name="shopee_url" 
                        value="{{ old('shopee_url') }}" 
                        class="form-control" 
                        required 
                        placeholder="https://shopee.co.id/product/40268086/22760826572"
                    >
                    <span class="form-help">Ketika pembeli menekan tombol 'Order di Shopee', pembeli akan langsung diarahkan ke halaman produk ini.</span>
                    @error('shopee_url')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nama & Kategori --}}
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Produk <span class="required">*</span></label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}" 
                            class="form-control" 
                            required 
                            placeholder="Contoh: Sweater Hoodie Metal Pria Terlaris"
                        >
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

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
                </div>

                {{-- Harga & Gambar --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="price" class="form-label">
                            Harga Produk di Shopee (Rupiah) <span class="required">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="price" 
                            name="price" 
                            value="{{ old('price') }}" 
                            class="form-control" 
                            required 
                            min="0"
                            step="1000"
                            placeholder="Contoh: 85000"
                        >
                        <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-top: 8px; align-items: center;">
                            <span style="font-size: 0.75rem; color: var(--slate-500); font-weight: 600;">⚡ Ubah Cepat 1-Klik:</span>
                            <button type="button" class="btn btn-outline" style="padding: 3px 8px; font-size: 0.75rem; border-radius: 4px;" onclick="setQuickPrice(45000)">Rp 45.000</button>
                            <button type="button" class="btn btn-outline" style="padding: 3px 8px; font-size: 0.75rem; border-radius: 4px;" onclick="setQuickPrice(75000)">Rp 75.000</button>
                            <button type="button" class="btn btn-outline" style="padding: 3px 8px; font-size: 0.75rem; border-radius: 4px;" onclick="setQuickPrice(119000)">Rp 119.000</button>
                            <button type="button" class="btn btn-outline" style="padding: 3px 8px; font-size: 0.75rem; border-radius: 4px;" onclick="setQuickPrice(125000)">Rp 125.000</button>
                            <button type="button" class="btn btn-outline" style="padding: 3px 8px; font-size: 0.75rem; border-radius: 4px;" onclick="setQuickPrice(159000)">Rp 159.000</button>
                        </div>
                        <span class="form-help">Harga otomatis terisi dari link Shopee. Anda juga bisa klik nominal di atas untuk mengganti harga secara instan.</span>
                        @error('price')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Foto Produk <span class="required">*</span></label>
                        
                        {{-- Tab Opsi Gambar --}}
                        <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                            <button type="button" class="btn btn-outline" id="btn_tab_file" onclick="switchImageTab('file')" style="padding: 4px 10px; font-size: 0.78rem;">
                                📁 Upload Foto
                            </button>
                            <button type="button" class="btn btn-primary" id="btn_tab_url" onclick="switchImageTab('url')" style="padding: 4px 10px; font-size: 0.78rem;">
                                🔗 Input URL Foto
                            </button>
                        </div>

                        {{-- Input URL Gambar --}}
                        <div id="image_url_group">
                            <input 
                                type="url" 
                                id="image_url" 
                                name="image_url" 
                                value="{{ old('image_url') }}" 
                                class="form-control" 
                                placeholder="Tempel URL gambar (misal dari Shopee atau CDN)"
                                oninput="updatePreviewImage(this.value)"
                            >
                            <span class="form-help">Bisa menyalin alamat gambar dari Shopee (Klik kanan foto Shopee &rarr; Copy image address) atau link foto lainnya.</span>
                        </div>

                        {{-- Input File Upload --}}
                        <div id="image_file_group" style="display: none;">
                            <input 
                                type="file" 
                                id="image_file" 
                                name="image_file" 
                                class="form-control" 
                                accept="image/png, image/jpeg, image/jpg, image/webp"
                                onchange="handleFileSelect(this)"
                            >
                            <span class="form-help">Pilih file foto dari laptop/HP Anda (screenshot/download foto dari Shopee).</span>
                        </div>

                        @error('image_url')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Live Image Preview --}}
                <div class="image-preview-wrapper" style="margin-top: 6px; margin-bottom: 20px;">
                    <div class="image-preview-box">
                        <img 
                            id="image_url_preview" 
                            src="{{ old('image_url') ?? asset('images/placeholder.svg') }}" 
                            alt="Preview Gambar"
                            onerror="handleImageError(this)"
                        >
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem; color: var(--slate-800);">Pratinjau Foto Produk</div>
                        <div style="font-size: 0.75rem; color: var(--slate-400);">Foto di atas yang akan tampil pada katalog toko pembeli.</div>
                        <span id="badge_shopee_photo" style="display: none; background: #ee4d2d; color: #fff; font-size: 0.72rem; font-weight: 700; padding: 3px 8px; border-radius: 999px; margin-top: 6px;">
                            ✓ Foto Resmi Shopee Terunduh
                        </span>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label for="description" class="form-label">Deskripsi Lengkap Produk <span class="required">*</span></label>
                    <textarea 
                        id="description" 
                        name="description" 
                        class="form-control" 
                        rows="5" 
                        required
                        placeholder="Detail spesifikasi bahan, potongan fitting, dan keunggulan..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Featured Checkbox --}}
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <span style="font-weight: 600; font-size: 0.9rem; color: var(--slate-800);">Jadikan sebagai Produk Pilihan / Unggulan di Beranda</span>
                    </label>
                </div>

                {{-- Submit Buttons --}}
                <div style="display: flex; gap: 12px; margin-top: 32px; border-top: 1px solid var(--slate-200); padding-top: 20px;">
                    <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-weight: 800; font-size: 0.95rem;">
                        ✓ Simpan & Publikasikan ke Katalog
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .price-highlight {
            border-color: #f97316 !important;
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.25) !important;
            transition: all 0.3s ease;
        }
    </style>

    <script>
        const quickInput = document.getElementById('quick_shopee_url');
        const quickPriceInput = document.getElementById('quick_price');
        const formShopeeInput = document.getElementById('shopee_url');
        const formPriceInput = document.getElementById('price');
        const btnExtract = document.getElementById('btn_extract');
        const loadingBox = document.getElementById('extract_loading');
        const statusBox = document.getElementById('extract_status');
        const previewImg = document.getElementById('image_url_preview');
        const urlInput = document.getElementById('image_url');

        // Helper to extract exact price from any text or share snippet
        function parsePriceFromText(text) {
            if (!text) return null;
            // 1. "seharga Rp45.000", "Rp 45.000", "harga 45000", "promo 45000", etc.
            const matchLabeled = text.match(/(?:rp|seharga|harga|promo|diskon|idr)\.?\s*([0-9.,]+)/i);
            if (matchLabeled) {
                const num = parseInt(matchLabeled[1].replace(/[^0-9]/g, ''), 10);
                if (num >= 1000) return num;
            }
            // 2. Formatted thousand dots "45.000" or "145.000" outside URL
            const textWithoutUrl = text.replace(/https?:\/\/[^\s]+/g, '');
            const matchDot = textWithoutUrl.match(/\b(\d{1,3}(?:\.\d{3})+)\b/);
            if (matchDot) {
                const num = parseInt(matchDot[1].replace(/\./g, ''), 10);
                if (num >= 1000) return num;
            }
            // 3. Shorthand "45k"
            const matchK = textWithoutUrl.match(/\b(\d{2,4})\s*k\b/i);
            if (matchK) return parseInt(matchK[1], 10) * 1000;
            // 4. Shorthand "45rb" or "45 ribu"
            const matchRb = textWithoutUrl.match(/\b(\d{2,3})\s*(?:ribu|rb)\b/i);
            if (matchRb) return parseInt(matchRb[1], 10) * 1000;
            // 5. Standalone numbers e.g. "45000"
            const matchNum = textWithoutUrl.match(/\b(\d{4,8})\b/);
            if (matchNum) {
                const num = parseInt(matchNum[1], 10);
                if (num >= 1000) return num;
            }
            return null;
        }

        // 1-Click Quick Price Selector
        window.setQuickPrice = function(val) {
            formPriceInput.value = val;
            if (quickPriceInput) quickPriceInput.value = val;
        };

        // Sync quick price input with form price input
        if (quickPriceInput) {
            quickPriceInput.addEventListener('input', function() {
                formPriceInput.value = this.value;
            });
        }
        if (formPriceInput) {
            formPriceInput.addEventListener('input', function() {
                if (quickPriceInput) quickPriceInput.value = this.value;
            });
        }

        // Auto extract on paste
        quickInput.addEventListener('paste', function(e) {
            setTimeout(() => {
                let val = quickInput.value.trim();
                // If text contains price (e.g. share snippet "seharga Rp45.000"), extract it
                const detected = parsePriceFromText(val);
                if (detected) {
                    if (quickPriceInput) quickPriceInput.value = detected;
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

        // Auto trigger if URL params present
        window.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            if (params.get('shopee_url')) {
                quickInput.value = params.get('shopee_url');
                if (params.get('price')) {
                    formPriceInput.value = params.get('price');
                    if (quickPriceInput) quickPriceInput.value = params.get('price');
                }
                setTimeout(triggerExtraction, 300);
            }
        });

        async function triggerExtraction() {
            const rawUrl = quickInput.value.trim();
            if (!rawUrl) {
                showStatus('Silakan tempelkan tautan produk Shopee terlebih dahulu.', 'error');
                quickInput.focus();
                return;
            }

            // Detect price if present in quick price input or share text
            const quickPriceVal = quickPriceInput ? quickPriceInput.value.trim() : '';
            const detectedPrice = quickPriceVal ? parseInt(quickPriceVal, 10) : parsePriceFromText(rawUrl);

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

                // Fill all form fields automatically
                formShopeeInput.value = d.shopee_url || rawUrl;
                document.getElementById('name').value = d.name || '';
                if (d.category_id) {
                    document.getElementById('category_id').value = d.category_id;
                }
                
                // Automatically populate price
                const finalPrice = d.price || detectedPrice;
                if (finalPrice) {
                    formPriceInput.value = finalPrice;
                    if (quickPriceInput) quickPriceInput.value = finalPrice;
                }

                // Set photo
                if (d.image_url) {
                    urlInput.value = d.image_url;
                    updatePreviewImage(d.image_url);
                    const photoBadge = document.getElementById('badge_shopee_photo');
                    if (photoBadge) photoBadge.style.display = 'inline-block';
                }

                if (d.description) {
                    document.getElementById('description').value = d.description;
                }

                if (finalPrice) {
                    const formatted = d.formatted_price || ('Rp ' + Number(finalPrice).toLocaleString('id-ID'));
                    showStatus('✓ Sempurna! Nama produk, foto resmi Shopee, kategori, dan nominal harga (' + formatted + ') berhasil otomatis terisi!', 'success');
                    document.getElementById('product_form').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                } else {
                    showStatus('✓ Foto resmi produk, nama, kategori, dan deskripsi BERHASIL otomatis diimpor! Silakan konfirmasi nominal harga promo produk di Shopee pada kolom harga di bawah.', 'warning');
                    formPriceInput.focus();
                    formPriceInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    formPriceInput.classList.add('price-highlight');
                    setTimeout(() => formPriceInput.classList.remove('price-highlight'), 3500);
                }
            } catch (err) {
                console.error(err);
                showStatus('Terjadi kesalahan saat memproses link Shopee. Silakan coba kembali.', 'error');
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
                btnFile.className = 'btn btn-primary';
                btnUrl.className = 'btn btn-outline';
            } else {
                fileGroup.style.display = 'none';
                urlGroup.style.display = 'block';
                btnFile.className = 'btn btn-outline';
                btnUrl.className = 'btn btn-primary';
            }
        }

        function handleFileSelect(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
                // Clear text url so backend knows file is used
                urlInput.value = '';
            }
        }

        function setLoading(isLoading) {
            if (isLoading) {
                loadingBox.style.display = 'flex';
                btnExtract.disabled = true;
                document.getElementById('btn_extract_text').textContent = 'Mengekstrak...';
            } else {
                loadingBox.style.display = 'none';
                btnExtract.disabled = false;
                document.getElementById('btn_extract_text').textContent = 'Ekstrak & Isi Otomatis';
            }
        }

        function showStatus(msg, type) {
            statusBox.textContent = msg;
            statusBox.style.display = 'block';
            if (type === 'success') {
                statusBox.style.backgroundColor = 'var(--emerald-50)';
                statusBox.style.color = 'var(--emerald-500)';
                statusBox.style.border = '1px solid #a7f3d0';
            } else if (type === 'error') {
                statusBox.style.backgroundColor = 'var(--rose-50)';
                statusBox.style.color = 'var(--rose-500)';
                statusBox.style.border = '1px solid #fecdd3';
            } else {
                statusBox.style.backgroundColor = 'var(--slate-100)';
                statusBox.style.color = 'var(--slate-700)';
                statusBox.style.border = '1px solid var(--slate-200)';
            }
        }

        function hideStatus() {
            statusBox.style.display = 'none';
        }
    </script>
@endsection
