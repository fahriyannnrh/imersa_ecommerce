<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="referrer" content="no-referrer">
    
    <title>@yield('title', 'Admin Panel - PT Imersa')</title>
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    {{-- Stylesheets --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ file_exists(public_path('css/admin.css')) ? filemtime(public_path('css/admin.css')) : time() }}">
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        {{-- Sidebar --}}
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <a href="{{ route('admin.dashboard') }}" style="display: flex; flex-direction: column; gap: 6px;">
                    <img src="{{ asset('images/logo-white.png') }}" alt="PT Imersa" style="height: 36px; width: auto; max-width: 160px; object-fit: contain;">
                    <span class="admin-brand-role" style="font-size: 0.7rem; letter-spacing: 0.05em; color: var(--slate-400);">ADMIN CONTROL PANEL</span>
                </a>
            </div>

            <nav class="admin-nav">
                <div class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="admin-nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.products.index') }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <span>Produk</span>
                    </a>
                </div>

                <div class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="8" y1="6" x2="21" y2="6"></line>
                            <line x1="8" y1="12" x2="21" y2="12"></line>
                            <line x1="8" y1="18" x2="21" y2="18"></line>
                            <line x1="3" y1="6" x2="3.01" y2="6"></line>
                            <line x1="3" y1="12" x2="3.01" y2="12"></line>
                            <line x1="3" y1="18" x2="3.01" y2="18"></line>
                        </svg>
                        <span>Kategori</span>
                    </a>
                </div>

                <div class="admin-nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reviews.index') }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <span>Ulasan</span>
                    </a>
                </div>

                <div class="admin-nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <a href="{{ route('admin.profile') }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Profil & Akun Google</span>
                    </a>
                </div>
            </nav>

            <div class="admin-nav-footer">
                <form action="{{ route('admin.logout') }}" method="POST" class="admin-nav-item">
                    @csrf
                    <button type="submit" style="color: var(--rose-500);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Area --}}
        <div class="admin-main">
            {{-- Topbar --}}
            <header class="admin-topbar">
                <div class="admin-topbar-title">
                    @yield('header_title', 'Admin Panel')
                </div>
                <div class="admin-topbar-actions">
                    <a href="{{ route('home') }}" target="_blank" class="btn-view-store">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                        <span>Lihat Toko</span>
                    </a>
                    <a href="{{ route('admin.profile') }}" title="Buka Pengaturan Profil & Akun Google" style="display: flex; align-items: center; gap: 8px; font-size: 0.875rem; font-weight: 700; color: var(--slate-800); text-decoration: none; padding: 6px 12px; border-radius: var(--radius-md); background: var(--slate-50); border: 1px solid var(--slate-200); transition: all 0.2s ease;">
                        @if (Auth::user() && Auth::user()->google_avatar)
                            <img src="{{ Auth::user()->google_avatar }}" alt="{{ Auth::user()->name }}" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
                        @else
                            <span>👤</span>
                        @endif
                        <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                        @if (Auth::user() && Auth::user()->isGoogleLinked())
                            <span title="Akun Google Terhubung" style="display: inline-flex; align-items: center; justify-content: center; width: 16px; height: 16px; border-radius: 50%; background: #22c55e; color: white; font-size: 10px; font-weight: 800;">✓</span>
                        @endif
                    </a>
                </div>
            </header>

            {{-- Content Area --}}
            <div class="admin-content">
                {{-- Flash Message Alerts --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error">
                        <div>
                            <strong>Terdapat kesalahan input:</strong>
                            <ul style="margin-top: 6px; padding-left: 18px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('js/app.js') }}?v={{ file_exists(public_path('js/app.js')) ? filemtime(public_path('js/app.js')) : time() }}"></script>
    @stack('scripts')
</body>
</html>
