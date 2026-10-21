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
                <input 
                    type="number" 
                    id="price" 
                    name="price" 
                    value="{{ old('price', $product->price) }}" 
                    class="form-control" 
                    required 
                    min="0"
                    step="1000"
                >
                <span class="form-help">Masukkan nominal angka tanpa titik atau tanda koma.</span>
                @error('price')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Foto Produk <span class="required">*</span></label>
                
                {{-- Tab Opsi Gambar --}}
                <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                    <button type="button" class="btn btn-primary" id="btn_tab_url" onclick="switchImageTab('url')" style="padding: 4px 10px; font-size: 0.78rem;">
                        🔗 URL Foto
                    </button>
                    <button type="button" class="btn btn-outline" id="btn_tab_file" onclick="switchImageTab('file')" style="padding: 4px 10px; font-size: 0.78rem;">
                        📁 Upload Foto Baru
                    </button>
                </div>

                {{-- Input URL Gambar --}}
                <div id="image_url_group">
                    <input 
                        type="url" 
                        id="image_url" 
                        name="image_url" 
                        value="{{ old('image_url', $product->image_url) }}" 
                        class="form-control" 
                        placeholder="Tempel URL gambar (misal dari Shopee CDN atau hosting foto)"
                        oninput="updatePreviewImage(this.value)"
                    >
                    <span class="form-help">Bisa menyalin URL foto produk langsung dari Shopee (Klik kanan foto Shopee &rarr; Copy image address).</span>
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
                    <span class="form-help">Pilih file foto dari laptop/HP Anda untuk mengganti foto produk saat ini.</span>
                </div>

                @error('image_url')
                    <div class="form-error">{{ $message }}</div>
                @enderror

                {{-- Live Image Preview --}}
                <div class="image-preview-wrapper" style="margin-top: 12px;">
                    <div class="image-preview-box">
                        <img 
                            id="image_url_preview" 
                            src="{{ old('image_url', $product->image_url) }}" 
                            alt="Preview Gambar"
                            onerror="handleImageError(this)"
                        >
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem; color: var(--slate-800);">Pratinjau Foto Produk</div>
                        <div style="font-size: 0.75rem; color: var(--slate-400);">Foto yang tampil pada katalog produk.</div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label for="shopee_url" class="form-label" style="margin-bottom: 0;">Tautan Langsung Produk di Shopee <span class="required">*</span></label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <button type="button" id="btn_sync_shopee" class="btn btn-outline" style="padding: 3px 10px; font-size: 0.78rem; font-weight: 700; color: #ee4d2d; border-color: #ee4d2d;" onclick="syncShopeeData()">
                            ⚡ Ambil Foto & Data dari Shopee
                        </button>
                        <span style="font-size: 0.75rem; color: var(--emerald-500); font-weight: 700;">✓ Direct Link</span>
                    </div>
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
                <span class="form-help">Tautan canonical Shopee langsung ke produk tanpa session query param.</span>
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
