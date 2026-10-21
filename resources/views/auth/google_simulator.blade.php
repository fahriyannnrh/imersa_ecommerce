<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Akun Google - PT Imersa</title>
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .google-account-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
            width: 100%;
            text-align: left;
            margin-bottom: 10px;
        }
        .google-account-item:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .google-avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            color: #ffffff;
            flex-shrink: 0;
        }
    </style>
</head>
<body style="background: #f8fafc;">
    <div class="auth-wrapper">
        <div class="auth-card" style="max-width: 460px; padding: 36px 32px;">
            <div style="text-align: center; margin-bottom: 24px;">
                {{-- Official Google 4-Color Logo --}}
                <svg width="40" height="40" viewBox="0 0 24 24" style="margin-bottom: 12px;">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>

                <h1 style="font-size: 1.35rem; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                    @if ($action === 'link')
                        Tautkan Akun Google
                    @else
                        Masuk dengan Google
                    @endif
                </h1>
                <p style="font-size: 0.875rem; color: #64748b;">
                    untuk melanjutkan ke <strong style="color: #2563eb;">PT Imersa</strong>
                </p>

                {{-- Dev Mode Notice Badge --}}
                <div style="display: inline-flex; align-items: center; gap: 6px; margin-top: 10px; padding: 4px 10px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 9999px; font-size: 0.75rem; color: #1d4ed8; font-weight: 600;">
                    <span>⚡ Mode Simulasi Google (Dev Mode Aktif)</span>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if ($errors->any())
                <div class="alert alert-error" style="margin-bottom: 16px;">
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <p style="font-size: 0.825rem; font-weight: 600; color: #475569; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                Pilih akun cepat:
            </p>

            {{-- Preset 1: Admin PT Imersa --}}
            <form action="{{ route('auth.google.simulator.post') }}" method="POST">
                @csrf
                <input type="hidden" name="origin" value="{{ $origin }}">
                <input type="hidden" name="name" value="Admin PT Imersa">
                <input type="hidden" name="email" value="admin@imersa.test">
                <button type="submit" class="google-account-item">
                    <div class="google-avatar-circle" style="background: #2563eb;">A</div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 700; font-size: 0.9rem; color: #1e293b;">Admin PT Imersa</div>
                        <div style="font-size: 0.8rem; color: #64748b; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">admin@imersa.test (Administrator)</div>
                    </div>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </form>

            {{-- Preset 2: Dimas Pratama (Customer) --}}
            <form action="{{ route('auth.google.simulator.post') }}" method="POST">
                @csrf
                <input type="hidden" name="origin" value="{{ $origin }}">
                <input type="hidden" name="name" value="Dimas Pratama">
                <input type="hidden" name="email" value="dimas.pratama@gmail.com">
                <button type="submit" class="google-account-item">
                    <div class="google-avatar-circle" style="background: #059669;">D</div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 700; font-size: 0.9rem; color: #1e293b;">Dimas Pratama</div>
                        <div style="font-size: 0.8rem; color: #64748b; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">dimas.pratama@gmail.com (Pelanggan)</div>
                    </div>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </form>

            {{-- Preset 3: Rani Wijaya (New User) --}}
            <form action="{{ route('auth.google.simulator.post') }}" method="POST">
                @csrf
                <input type="hidden" name="origin" value="{{ $origin }}">
                <input type="hidden" name="name" value="Rani Wijaya">
                <input type="hidden" name="email" value="rani.wijaya@gmail.com">
                <button type="submit" class="google-account-item">
                    <div class="google-avatar-circle" style="background: #db2777;">R</div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 700; font-size: 0.9rem; color: #1e293b;">Rani Wijaya</div>
                        <div style="font-size: 0.8rem; color: #64748b; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">rani.wijaya@gmail.com (Pelanggan Baru)</div>
                    </div>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </form>

            {{-- Custom Google Account Form --}}
            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px dashed #cbd5e1;">
                <p style="font-size: 0.825rem; font-weight: 600; color: #475569; margin-bottom: 10px;">
                    Atau gunakan nama & Gmail Anda sendiri:
                </p>
                <form action="{{ route('auth.google.simulator.post') }}" method="POST">
                    @csrf
                    <input type="hidden" name="origin" value="{{ $origin }}">
                    <div style="margin-bottom: 10px;">
                        <input 
                            type="text" 
                            name="name" 
                            class="form-control" 
                            placeholder="Nama Lengkap Anda" 
                            required
                            style="font-size: 0.875rem; padding: 10px 14px;"
                        >
                    </div>
                    <div style="margin-bottom: 12px;">
                        <input 
                            type="email" 
                            name="email" 
                            class="form-control" 
                            placeholder="Alamat Gmail Anda (contoh: nama@gmail.com)" 
                            required
                            style="font-size: 0.875rem; padding: 10px 14px;"
                        >
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px 16px; font-size: 0.9rem;">
                        Masuk / Tautkan Akun Ini
                    </button>
                </form>
            </div>

            {{-- Back Link --}}
            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ $origin === 'admin' ? route('admin.login') : route('login') }}" style="font-size: 0.825rem; color: var(--slate-500); text-decoration: underline;">
                    &larr; Batalkan dan kembali
                </a>
            </div>
        </div>
    </div>
</body>
</html>
