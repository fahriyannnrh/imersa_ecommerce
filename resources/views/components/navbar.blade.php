<header class="site-header">
    <div class="container">
        <nav class="navbar">
            {{-- Brand Logo --}}
            <a href="{{ route('home') }}" class="brand-logo" title="PT Imersa - Beranda">
                <img src="{{ asset('images/logo.png') }}" alt="PT Imersa Logo" class="brand-logo-img">
            </a>

            {{-- Desktop Navigation Menu --}}
            <ul class="nav-menu">
                <li>
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                </li>
                <li>
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">Katalog Produk</a>
                </li>
                <li>
                    <a href="{{ route('home') }}#tentang" class="nav-link">Tentang Kami</a>
                </li>
                <li>
                    <a href="{{ route('home') }}#kontak" class="nav-link">Kontak</a>
                </li>
            </ul>

            {{-- Actions & Search --}}
            <div class="nav-actions">
                <form action="{{ route('products.index') }}" method="GET" class="nav-search">
                    <span class="nav-search-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input 
                        type="search" 
                        name="search" 
                        placeholder="Cari pakaian..." 
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                </form>

                {{-- User / Admin Auth Actions --}}
                @auth
                    <div style="display: flex; align-items: center; gap: 8px;">
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn-admin-nav" title="Buka Dashboard Admin">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="14" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        @else
                            <div style="display: flex; align-items: center; gap: 8px; padding: 4px 12px 4px 6px; background: #f8fafc; border-radius: var(--radius-full); border: 1px solid var(--slate-200);">
                                <img src="{{ Auth::user()->getAvatarUrl() }}" alt="{{ Auth::user()->name }}" style="width: 26px; height: 26px; border-radius: 50%; object-fit: cover;">
                                <span style="font-size: 0.825rem; font-weight: 600; color: var(--slate-700); max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ Auth::user()->name }}
                                </span>
                            </div>
                        @endif

                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="btn-admin-nav" style="padding: 8px 12px; color: var(--rose-500); border-color: #fecdd3; background-color: #fff1f2; cursor: pointer;" title="Keluar dari Akun">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-admin-nav" title="Masuk ke Akun PT Imersa">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Login Akun</span>
                    </a>
                @endauth

                {{-- Shopee Store Button --}}
                <a href="https://shopee.co.id/search?keyword=baju+clothing+imersa" target="_blank" rel="noopener noreferrer" class="btn-shopee-nav" title="Buka Toko Shopee">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19.38 6.64H16.5a4.5 4.5 0 0 0-9 0H4.62A1.63 1.63 0 0 0 3 8.27l1.37 12.35A2.45 2.45 0 0 0 6.82 23h10.36a2.45 2.45 0 0 0 2.45-2.38L21 8.27a1.63 1.63 0 0 0-1.62-1.63zM12 3.75a2.88 2.88 0 0 1 2.87 2.89H9.13A2.88 2.88 0 0 1 12 3.75zm6 16.75a.82.82 0 0 1-.82.78H6.82a.82.82 0 0 1-.82-.78L4.69 8.27H19.3z"/>
                    </svg>
                    <span>Shopee Store</span>
                </a>

                {{-- Mobile Hamburger Button --}}
                <button type="button" class="mobile-toggle" id="mobileToggle" aria-label="Menu Navigasi">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </nav>
    </div>

    {{-- Mobile Dropdown Drawer --}}
    <div class="mobile-drawer" id="mobileDrawer">
        <form action="{{ route('products.index') }}" method="GET" style="margin-bottom: 16px;">
            <input 
                type="search" 
                name="search" 
                placeholder="Cari pakaian..." 
                value="{{ request('search') }}"
                class="form-control"
                style="padding: 10px 16px; border-radius: var(--radius-full);"
            >
        </form>
        <ul class="mobile-menu-links">
            <li><a href="{{ route('home') }}" class="nav-link">Beranda</a></li>
            <li><a href="{{ route('products.index') }}" class="nav-link">Katalog Produk</a></li>
            <li><a href="{{ route('home') }}#tentang" class="nav-link">Tentang Kami</a></li>
            <li><a href="{{ route('home') }}#kontak" class="nav-link">Kontak</a></li>
            @auth
                @if (Auth::user()->isAdmin())
                    <li><a href="{{ route('admin.dashboard') }}" class="nav-link" style="color: var(--primary); font-weight: 700;">Dashboard Admin</a></li>
                @else
                    <li style="padding: 8px 0; color: var(--slate-600); font-weight: 600;">
                        Halo, {{ Auth::user()->name }}
                    </li>
                @endif
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: var(--rose-500); font-weight: 700; cursor: pointer; padding: 8px 0; font-size: 1rem;">
                            Logout
                        </button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}" class="nav-link" style="color: var(--primary); font-weight: 700;">Login Akun</a></li>
                <li><a href="{{ route('register') }}" class="nav-link" style="color: var(--slate-700); font-weight: 600;">Daftar Akun Baru</a></li>
            @endauth
        </ul>
        <a href="https://shopee.co.id/search?keyword=baju+clothing+imersa" target="_blank" rel="noopener noreferrer" class="btn-shopee-nav" style="width: 100%; justify-content: center; padding: 12px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19.38 6.64H16.5a4.5 4.5 0 0 0-9 0H4.62A1.63 1.63 0 0 0 3 8.27l1.37 12.35A2.45 2.45 0 0 0 6.82 23h10.36a2.45 2.45 0 0 0 2.45-2.38L21 8.27a1.63 1.63 0 0 0-1.62-1.63zM12 3.75a2.88 2.88 0 0 1 2.87 2.89H9.13A2.88 2.88 0 0 1 12 3.75zm6 16.75a.82.82 0 0 1-.82.78H6.82a.82.82 0 0 1-.82-.78L4.69 8.27H19.3z"/>
            </svg>
            <span>Beli di Shopee</span>
        </a>
    </div>
</header>
