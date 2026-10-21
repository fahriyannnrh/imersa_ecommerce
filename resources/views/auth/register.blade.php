<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - PT Imersa</title>
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .btn-google-auth:hover {
            background-color: #f8fafc !important;
            border-color: #cbd5e1 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            outline: none;
        }
        .auth-switch-link {
            font-size: 0.875rem;
            color: var(--slate-600);
            text-align: center;
            margin-top: 18px;
        }
        .auth-switch-link a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: underline;
        }
        .auth-switch-link a:hover {
            color: var(--primary-hover);
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <a href="{{ route('login') }}" title="PT Imersa">
                    <img src="{{ asset('images/logo.png') }}" alt="PT Imersa" style="height: 48px; width: auto; max-width: 200px; object-fit: contain; margin-bottom: 16px;">
                </a>
                <h1 class="auth-title">Daftar Akun Baru</h1>
                <p class="auth-subtitle">Daftar akun untuk menjelajahi katalog dan koleksi produk PT Imersa</p>
            </div>

            {{-- Flash Messages --}}
            @if (session('error'))
                <div class="alert alert-error" style="margin-bottom: 20px;">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error" style="margin-bottom: 20px;">
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Sign up with Google Button --}}
            <a href="{{ route('auth.google', ['origin' => 'user']) }}" class="btn-google-auth" style="display: flex; align-items: center; justify-content: center; gap: 12px; width: 100%; padding: 12px 16px; background-color: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 8px; font-weight: 600; font-size: 0.95rem; color: #1e293b; text-decoration: none; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.06); margin-bottom: 18px;">
                <svg width="20" height="20" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Daftar dengan Akun Google</span>
            </a>

            <div style="display: flex; align-items: center; margin: 18px 0; gap: 12px; color: #94a3b8; font-size: 0.825rem;">
                <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
                <span>atau daftar dengan formulir</span>
                <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
            </div>

            <form action="{{ route('register.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        class="form-control" 
                        required 
                        autofocus
                        placeholder="contoh: Dimas Pratama"
                    >
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email <span class="required">*</span></label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        class="form-control" 
                        required 
                        placeholder="contoh: dimas@gmail.com"
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password <span class="required">*</span></label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        required
                        placeholder="Minimal 8 karakter"
                    >
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="required">*</span></label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-control" 
                        required
                        placeholder="Ketik ulang password"
                    >
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 1rem;">
                    Daftar Sekarang
                </button>

                <div class="auth-switch-link">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}">
                        Masuk di sini
                    </a>
                </div>

                <div style="text-align: center; margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--slate-100);">
                    <a href="{{ route('login') }}" style="font-size: 0.825rem; color: var(--slate-500); font-weight: 500;">
                        &larr; Kembali ke Halaman Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
