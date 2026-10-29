<footer class="site-footer" id="kontak">
    <div class="container">
        <div class="footer-grid">
            {{-- Col 1: Brand Info --}}
            <div>
                <div class="footer-brand-logo">
                    <img src="{{ asset('images/logo-white.png') }}" alt="PT Imersa" style="height: 42px; width: auto; max-width: 180px; object-fit: contain; margin-bottom: 16px;">
                </div>
                <p class="footer-brand-desc">
                    Brand clothing modern asal Indonesia yang menghadirkan pakaian berkualitas tinggi, nyaman dipakai sehari-hari, dan memiliki desain kontemporer autentik.
                </p>
                <div style="font-size: 0.85rem; color: var(--slate-400); line-height: 1.5;">
                    <p>📍 Prenggan, Kec. Kotagede, Kota Yogyakarta, Daerah Istimewa Yogyakarta</p>
                </div>
            </div>

            {{-- Col 2: Kategori --}}
            <div>
                <h4 class="footer-title">Kategori Pilihan</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('products.index', ['category' => 'kaos']) }}">Kaos & T-Shirt</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'kemeja']) }}">Kemeja Casual</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'celana']) }}">Celana & Denim</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'jaket-hoodie']) }}">Jaket & Outerwear</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'aksesoris']) }}">Aksesoris</a></li>
                </ul>
            </div>

            {{-- Col 3: Navigasi Cepat --}}
            <div>
                <h4 class="footer-title">Navigasi</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('products.index') }}">Katalog Produk</a></li>
                    <li><a href="{{ route('home') }}#tentang">Tentang PT Imersa</a></li>
                    <li><a href="{{ route('home') }}#kontak">Hubungi Kami</a></li>
                    <li><a href="{{ route('admin.login') }}">Admin Portal</a></li>
                </ul>
            </div>

            {{-- Col 4: Official Shopee Store --}}
            <div>
                <h4 class="footer-title">Official Shopee Store</h4>
                <p style="font-size: 0.9rem; line-height: 1.6; margin-bottom: 16px;">
                    Dapatkan penawaran promo ongkir gratis, voucher diskon menarik, dan kemudahan pembayaran resmi melalui akun Shopee Official PT Imersa.
                </p>
                <a href="https://shopee.co.id" target="_blank" rel="noopener noreferrer" class="shopee-badge-footer">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19.38 6.64H16.5a4.5 4.5 0 0 0-9 0H4.62A1.63 1.63 0 0 0 3 8.27l1.37 12.35A2.45 2.45 0 0 0 6.82 23h10.36a2.45 2.45 0 0 0 2.45-2.38L21 8.27a1.63 1.63 0 0 0-1.62-1.63zM12 3.75a2.88 2.88 0 0 1 2.87 2.89H9.13A2.88 2.88 0 0 1 12 3.75zm6 16.75a.82.82 0 0 1-.82.78H6.82a.82.82 0 0 1-.82-.78L4.69 8.27H19.3z"/>
                    </svg>
                    <span>Kunjungi Toko di Shopee</span>
                </a>
            </div>
        </div>

        <div class="footer-bottom">
            <div>
                &copy; {{ date('Y') }} PT Imersa. Seluruh hak cipta dilindungi undang-undang.
            </div>
            <div style="color: var(--slate-500); font-size: 0.8rem;">
                Katalog Resmi Clothing PT Imersa · Powered by Laravel Blade
            </div>
        </div>
    </div>
</footer>
