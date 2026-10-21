@extends('layouts.admin')

@section('title', 'Pengaturan Profil & Tautkan Akun Google - PT Imersa')
@section('header_title', 'Pengaturan Akun & Profil')

@section('content')
    <div class="profile-grid">
        
        {{-- Left Column: Google Account Linking (Card Prioritas) --}}
        <div>
            {{-- Google Integration Card --}}
            <div class="admin-card" style="border: 2px solid {{ $user->isGoogleLinked() ? '#bbf7d0' : '#e2e8f0' }}; position: relative; overflow: hidden;">
                @if ($user->isGoogleLinked())
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #4285F4, #34A853, #FBBC05, #EA4335);"></div>
                @endif

                <div class="admin-card-header" style="margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 44px; height: 44px; border-radius: 10px; background-color: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center;">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="admin-card-title" style="margin: 0; font-size: 1.15rem;">Tautkan Akun Google</h2>
                            <p style="font-size: 0.825rem; color: var(--slate-500); margin: 0;">Integrasi Single Sign-On (SSO) Google</p>
                        </div>
                    </div>

                    @if ($user->isGoogleLinked())
                        <span class="badge" style="background-color: #dcfce7; color: #15803d; font-weight: 700; padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem;">
                            ✓ Terhubung
                        </span>
                    @else
                        <span class="badge" style="background-color: #f1f5f9; color: #64748b; font-weight: 600; padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem;">
                            Belum Tertaut
                        </span>
                    @endif
                </div>

                @if ($user->isGoogleLinked())
                    {{-- Status Tertaut --}}
                    <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            @if ($user->google_avatar)
                                <img src="{{ $user->google_avatar }}" alt="Google Avatar" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid #22c55e;">
                            @else
                                <div style="width: 48px; height: 48px; border-radius: 50%; background: #22c55e; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem;">
                                    G
                                </div>
                            @endif
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-weight: 700; color: #166534; font-size: 0.95rem;">
                                    Akun Google Anda Aktif
                                </div>
                                <div style="font-size: 0.825rem; color: #15803d; margin-top: 2px; word-break: break-all;">
                                    ID Google: <code style="background: rgba(255,255,255,0.7); padding: 2px 6px; border-radius: 4px;">{{ $user->google_id }}</code>
                                </div>
                            </div>
                        </div>
                        <p style="font-size: 0.85rem; color: #166534; margin-top: 12px; margin-bottom: 0;">
                            Anda dapat masuk ke Dashboard Admin PT Imersa secara langsung hanya dengan 1-klik menggunakan tombol <strong>Masuk dengan Google</strong>.
                        </p>
                    </div>

                    {{-- Tombol Lepas Tautan --}}
                    <form action="{{ route('auth.google.unlink') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin melepas tautan akun Google ini? Pastikan Anda mengingat email dan password akun Anda.');">
                        @csrf
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                            <span style="font-size: 0.8rem; color: var(--slate-500);">
                                Lepas tautan jika Anda ingin mengganti akun Google atau menggunakan login manual.
                            </span>
                            <button type="submit" class="btn" style="background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; font-size: 0.875rem; padding: 8px 16px; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                Lepas Tautan Google
                            </button>
                        </div>
                    </form>
                @else
                    {{-- Status Belum Tertaut --}}
                    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                        <p style="font-size: 0.875rem; color: var(--slate-700); margin: 0 0 10px 0; line-height: 1.5;">
                            Tautkan akun Google Anda sekarang agar Anda dapat login dengan lebih cepat, aman, dan tanpa perlu repot mengetik password setiap kali masuk.
                        </p>
                        <ul style="font-size: 0.825rem; color: var(--slate-600); margin: 0; padding-left: 20px; line-height: 1.6;">
                            <li>Login 1-klik langsung dari halaman depan login admin.</li>
                            <li>Keamanan terjamin menggunakan autentikasi resmi Google OAuth 2.0.</li>
                            <li>Bisa dilepas kapan saja jika diinginkan.</li>
                        </ul>
                    </div>

                    @if ($isGoogleConfigured)
                        <a href="{{ route('auth.google') }}" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 12px 20px; background-color: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 8px; font-weight: 700; font-size: 0.95rem; color: #1e293b; text-decoration: none; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: all 0.2s ease;">
                            <svg width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                            <span>Tautkan dengan Akun Google</span>
                        </a>
                    @else
                        <div style="background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 14px; margin-bottom: 14px;">
                            <div style="display: flex; gap: 10px; align-items: start;">
                                <span style="font-size: 1.25rem;">⚠️</span>
                                <div style="font-size: 0.85rem; color: #92400e; line-height: 1.5;">
                                    <strong>Kredensial Google OAuth Belum Diisi</strong><br>
                                    Untuk mengaktifkan tombol tautkan Google, isi variabel berikut di file <code>.env</code> aplikasi:
                                    <pre style="background: rgba(0,0,0,0.05); padding: 8px; border-radius: 6px; margin: 8px 0 0 0; font-size: 0.8rem; overflow-x: auto;">GOOGLE_CLIENT_ID=kredensial_client_id_anda
GOOGLE_CLIENT_SECRET=kredensial_client_secret_anda
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"</pre>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('auth.google') }}" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 12px 20px; background-color: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 8px; font-weight: 700; font-size: 0.95rem; color: #1e293b; text-decoration: none; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                            <svg width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                            <span>Tautkan Akun Google Sekarang</span>
                        </a>
                    @endif
                @endif
            </div>

            {{-- Cara Setup Google OAuth Guide Card --}}
            <div class="admin-card" style="background-color: #fafbfd;">
                <h3 style="font-size: 0.95rem; font-weight: 700; color: var(--slate-800); margin-bottom: 8px;">
                    💡 Panduan Setup Google Console (Bagi Admin Pengembang)
                </h3>
                <p style="font-size: 0.825rem; color: var(--slate-600); margin-bottom: 12px; line-height: 1.5;">
                    Jika belum memiliki <em>Client ID & Secret</em>, Anda bisa membuatnya gratis di Google Cloud Console:
                </p>
                <ol style="font-size: 0.8rem; color: var(--slate-600); padding-left: 18px; line-height: 1.6; margin: 0;">
                    <li>Buka <a href="https://console.cloud.google.com/apis/credentials" target="_blank" style="color: var(--primary); text-decoration: underline;">Google Cloud Console</a>.</li>
                    <li>Buat Project baru, lalu klik <strong>Create Credentials &gt; OAuth client ID</strong>.</li>
                    <li>Pilih Application Type: <strong>Web application</strong>.</li>
                    <li>Pada <strong>Authorized redirect URIs</strong>, masukkan:<br>
                        <code style="background: var(--slate-200); padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px; word-break: break-all;">
                            {{ config('services.google.redirect') }}
                        </code>
                    </li>
                    <li>Salin <strong>Client ID</strong> dan <strong>Client Secret</strong> ke file <code>.env</code> Anda.</li>
                </ol>
            </div>
        </div>

        {{-- Right Column: Informasi Profil & Ubah Password --}}
        <div>
            {{-- Form Edit Data Profil --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Informasi Akun</h2>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $user->name) }}" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Administrator <span class="required">*</span></label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', $user->email) }}" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Form Ganti / Set Password --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div>
                        <h2 class="admin-card-title">Keamanan Password</h2>
                        <p style="font-size: 0.825rem; color: var(--slate-500); margin: 0;">
                            {{ $user->hasPassword() ? 'Ubah password login manual Anda secara berkala.' : 'Buat password manual untuk login dengan email & password.' }}
                        </p>
                    </div>
                </div>

                @if (!$user->hasPassword())
                    <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px; margin-bottom: 16px; font-size: 0.85rem; color: #1e40af;">
                        ℹ️ Anda saat ini terdaftar via Google dan belum memiliki password manual. Buat password baru di bawah ini agar Anda memiliki opsi login ganda (Google atau Email & Password).
                    </div>
                @endif

                <form action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if ($user->hasPassword())
                        <div class="form-group">
                            <label for="current_password" class="form-label">Password Saat Ini <span class="required">*</span></label>
                            <input 
                                type="password" 
                                id="current_password" 
                                name="current_password" 
                                class="form-control" 
                                required
                                placeholder="••••••••"
                            >
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="password" class="form-label">Password Baru <span class="required">*</span></label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control" 
                            required
                            minlength="8"
                            placeholder="Minimal 8 karakter"
                        >
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="required">*</span></label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="form-control" 
                            required
                            minlength="8"
                            placeholder="Ulangi password baru"
                        >
                    </div>

                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                            {{ $user->hasPassword() ? 'Perbarui Password' : 'Buat Password Akun' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
