@extends('layouts.admin')

@section('title', 'Edit Produk - ' . $product->name)
@section('header_title', 'Edit Produk')

@section('content')
    <div class="admin-card" style="max-width: 800px;">
        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Edit Data: {{ $product->name }}</h3>
                <p style="font-size: 0.85rem; color: var(--slate-500);">Perbarui informasi produk katalog</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline" style="padding: 8px 14px; font-size: 0.85rem;">
                &larr; Kembali ke Daftar
            </a>
        </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="name" class="form-label">Nama Produk <span class="required">*</span></label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $product->name) }}" 
                        class="form-control" 
                        required 
                    >
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category_id" class="form-label">Kategori <span class="required">*</span></label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="price" class="form-label">Harga (Rupiah) <span class="required">*</span></label>
                <div class="price-input-wrapper">
                    <span class="price-prefix">Rp</span>
                    <input 
                        type="number" 
                        id="price" 
                        name="price" 
                        value="{{ old('price', $product->price) }}" 
                        class="form-control price-input" 
                        required 
                        min="0"
                        step="1000"
                        placeholder="0"
                    >
                </div>
                <span class="form-help">Masukkan nominal angka harga produk di katalog.</span>
                @error('price')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Foto Produk & Alamat Hotlink (Bersanding berdampingan secara langsung!) --}}
            <div class="photo-hotlink-card" style="margin-bottom: 20px;">
                <div class="photo-hotlink-header">
                    <div>
                        <label class="form-label" style="margin-bottom: 2px;">
                            Foto Produk & Alamat Hotlink <span class="required">*</span>
                        </label>
                        <span style="font-size: 0.8rem; color: var(--slate-500);">
                            Pratinjau foto langsung menyesuaikan dengan alamat hotlink URL gambar produk.
                        </span>
                    </div>
                    <div class="tab-pill-group" style="margin-bottom: 0;">
                        <button type="button" class="tab-pill active" id="btn_tab_url" onclick="switchImageTab('url')">
                            🔗 Alamat Hotlink (URL)
                        </button>
                        <button type="button" class="tab-pill" id="btn_tab_file" onclick="switchImageTab('file')">
                            📁 Upload File Baru
                        </button>
                    </div>
                </div>

                <div class="photo-hotlink-body">
                    {{-- Pratinjau Foto --}}
                    <div class="photo-preview-wrap">
                        <div class="photo-preview-box" id="preview_box">
                            <img 
                                id="image_url_preview" 
                                src="{{ old('image_url', $product->image_url) }}" 
                                alt="Pratinjau Foto {{ $product->name }}" 
                                onerror="handleImagePreviewError(this)"
                            >
                            <div class="preview-spinner" id="preview_spinner"></div>
                        </div>
                        <span class="photo-preview-tag">Foto Aktif</span>
                    </div>

                    {{-- Input Alamat Hotlink / File --}}
                    <div class="photo-input-wrap">
                        <div id="image_url_group">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px; flex-wrap: wrap; gap: 6px;">
                                <label for="image_url" style="font-size: 0.82rem; font-weight: 700; color: var(--slate-700); margin: 0;">
                                    Alamat Hotlink Gambar (URL):
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
                                value="{{ old('image_url', $product->image_url) }}" 
                                class="form-control" 
                                placeholder="https://... tempel alamat hotlink gambar (Shopee CDN / hosting foto)"
                                autocomplete="off"
                            >
                            <div id="hotlink_status" class="hotlink-status-badge idle">
                                <span class="badge-icon">ℹ️</span>
                                <span id="hotlink_status_text">Mendukung alamat URL gambar langsung. Gambar di samping akan otomatis menyesuaikan secara langsung saat link ditempel.</span>
                            </div>
                        </div>

                        <div id="image_file_group" style="display: none;">
                            <label for="image_file" style="font-size: 0.82rem; font-weight: 700; color: var(--slate-700); margin-bottom: 4px; display: block;">
                                Pilih File Foto Baru dari Komputer:
                            </label>
                            <input 
                                type="file" 
                                id="image_file" 
                                name="image_file" 
                                class="form-control" 
                                accept="image/png, image/jpeg, image/jpg, image/webp"
                                onchange="handleFileSelect(this)"
                            >
                            <span class="form-help">Pilih file foto untuk mengganti foto produk saat ini (JPG, PNG, WEBP).</span>
                        </div>
                    </div>
                </div>

                @error('image_url')
                    <div class="form-error" style="margin-top: 8px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; flex-wrap: wrap; gap: 8px;">
                    <label for="shopee_url" class="form-label" style="margin-bottom: 0;">Tautan Produk di Shopee <span class="required">*</span></label>
                    <button type="button" id="btn_sync_shopee" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.78rem; font-weight: 700; color: #ee4d2d; border-color: #ffd8cc; background: #fff5f2;" onclick="syncShopeeData()">
                        ⚡ Sinkron Data dari Shopee
                    </button>
                </div>
                <input 
                    type="url" 
                    id="shopee_url" 
                    name="shopee_url" 
                    value="{{ old('shopee_url', $product->shopee_url) }}" 
                    class="form-control" 
                    required 
                    placeholder="https://shopee.co.id/product/40268086/22760826572"
                >
                <div id="sync_status" style="display: none; margin-top: 6px; padding: 6px 12px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;"></div>
                <span class="form-help">Tautan tujuan ketika pengunjung mengklik tombol beli di halaman web.</span>
                @error('shopee_url')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi Lengkap Produk <span class="required">*</span></label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control" 
                    rows="6" 
                    required
                >{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                    <span style="font-weight: 600; font-size: 0.9rem; color: var(--slate-800);">Jadikan sebagai Produk Pilihan / Unggulan (Tampil di Beranda)</span>
                </label>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 32px; border-top: 1px solid var(--slate-200); padding-top: 20px;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-weight: 800;">
                    ✓ Perbarui Data Produk
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" style="padding: 12px 20px;">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        const previewImg = document.getElementById('image_url_preview');
        const urlInput = document.getElementById('image_url');
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
                setHotlinkStatus('idle', 'Mendukung alamat URL gambar langsung. Gambar di samping akan otomatis menyesuaikan secara langsung saat link ditempel.');
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
                        
                        const shopeeInput = document.getElementById('shopee_url');
                        if (shopeeInput && !shopeeInput.value) shopeeInput.value = cleaned;
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
            setHotlinkStatus('idle', 'Mendukung alamat URL gambar langsung. Gambar di samping akan otomatis menyesuaikan secara langsung saat link ditempel.');
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
                btnFile.className = 'tab-pill active';
                btnUrl.className = 'tab-pill';
            } else {
                fileGroup.style.display = 'none';
                urlGroup.style.display = 'block';
                btnFile.className = 'tab-pill';
                btnUrl.className = 'tab-pill active';
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

        async function syncShopeeData() {
            const shopeeUrl = document.getElementById('shopee_url').value.trim();
            const btn = document.getElementById('btn_sync_shopee');
            const statusBox = document.getElementById('sync_status');

            if (!shopeeUrl) {
                alert('Silakan masukkan link produk Shopee terlebih dahulu.');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Mengekstrak...';
            statusBox.style.display = 'none';

            try {
                const response = await fetch("{{ route('admin.products.extract_shopee') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ shopee_url: shopeeUrl })
                });

                const res = await response.json();
                if (!response.ok || !res.success) {
                    throw new Error(res.message || 'Gagal mengambil data Shopee.');
                }

                const d = res.data;
                if (d.image_url) {
                    urlInput.value = d.image_url;
                    updatePreviewImage(d.image_url);
                    switchImageTab('url');
                }
                if (d.price) {
                    document.getElementById('price').value = d.price;
                }
                if (d.category_id) {
                    document.getElementById('category_id').value = d.category_id;
                }

                if (d.price) {
                    statusBox.textContent = '✓ Foto resmi produk, data Shopee, dan nominal harga (' + (d.formatted_price || ('Rp ' + Number(d.price).toLocaleString('id-ID'))) + ') berhasil disinkronkan!';
                } else {
                    statusBox.textContent = '✓ Foto resmi produk, nama, dan data Shopee berhasil diperbarui! Nominal harga yang sudah tersimpan tetap dipertahankan.';
                }
                statusBox.style.display = 'block';
                statusBox.style.backgroundColor = 'var(--emerald-50)';
                statusBox.style.color = 'var(--emerald-600)';
                statusBox.style.border = '1px solid #a7f3d0';
            } catch (err) {
                statusBox.textContent = 'Gagal sinkron: ' + err.message;
                statusBox.style.display = 'block';
                statusBox.style.backgroundColor = 'var(--rose-50)';
                statusBox.style.color = 'var(--rose-600)';
                statusBox.style.border = '1px solid #fecdd3';
            } finally {
                btn.disabled = false;
                btn.textContent = '⚡ Ambil Foto & Data dari Shopee';
            }
        }
    </script>
@endsection
