@extends('layouts.admin')

@section('title', 'Pengaturan Profil & Tautkan Akun Google - PT Imersa')
@section('header_title', 'Pengaturan Akun & Profil')

@section('content')
    <div class="profile-grid">
        
        {{-- Left Column: Google Account Linking (Card Prioritas) --}}
        <div>
            {{-- Google Integration Card --}}
            <div class="admin-card" style="border: 1px solid {{ $user->isGoogleLinked() ? '#86efac' : '#e2e8f0' }}; position: relative; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);">
                @if ($user->isGoogleLinked())
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #4285F4 25%, #34A853 25% 50%, #FBBC05 50% 75%, #EA4335 75%);"></div>
                @endif

                <div class="admin-card-header" style="margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1px solid var(--slate-100);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: #ffffff; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                            <svg width="22" height="22" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="admin-card-title" style="margin: 0; font-size: 1.1rem; line-height: 1.3;">Tautkan Akun Google</h2>
                            <p style="font-size: 0.8rem; color: var(--slate-500); margin: 2px 0 0 0;">Single Sign-On (SSO) Google OAuth 2.0</p>
                        </div>
                    </div>

                    @if ($user->isGoogleLinked())
                        <span class="badge badge-success">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Terhubung
                        </span>
                    @else
                        <span class="badge badge-neutral">Belum Tertaut</span>
                    @endif
                </div>

                @if ($user->isGoogleLinked())
                    {{-- Status Tertaut --}}
                    <div style="background: linear-gradient(180deg, #f0fdf4 0%, #ecfdf5 100%); border: 1px solid #bbf7d0; border-radius: 12px; padding: 18px; margin-bottom: 20px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            @if ($user->google_avatar)
                                <img src="{{ $user->google_avatar }}" alt="Google Avatar" style="width: 52px; height: 52px; border-radius: 50%; object-fit: cover; border: 2.5px solid #22c55e; box-shadow: 0 2px 6px rgba(34, 197, 94, 0.2);">
                            @else
                                <div style="width: 52px; height: 52px; border-radius: 50%; background: #22c55e; color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.25rem;">
                                    G
                                </div>
                            @endif
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <span style="font-weight: 800; color: #166534; font-size: 0.95rem;">Akun Google Aktif</span>
                                    <span style="background: #22c55e; color: #ffffff; border-radius: 50%; width: 14px; height: 14px; display: inline-flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 900;">✓</span>
                                </div>
                                <div style="font-size: 0.8rem; color: #15803d; margin-top: 4px; word-break: break-all; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                    <span>ID Google:</span>
                                    <code style="background: rgba(255,255,255,0.85); padding: 2px 8px; border-radius: 6px; border: 1px solid #bbf7d0; font-size: 0.75rem; color: #14532d; font-weight: 600;">{{ $user->google_id }}</code>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed rgba(34, 197, 94, 0.3);">
                            <p style="font-size: 0.825rem; color: #166534; margin: 0; line-height: 1.5;">
                                ✨ Anda dapat masuk ke Dashboard Admin PT Imersa secara langsung hanya dengan 1-klik menggunakan tombol <strong>Masuk dengan Google</strong>.
                            </p>
                        </div>
                    </div>

                    {{-- Tombol Lepas Tautan --}}
                    <form action="{{ route('auth.google.unlink') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin melepas tautan akun Google ini? Pastikan Anda sudah membuat password manual untuk tetap bisa login.');">
                        @csrf
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                            <span style="font-size: 0.8rem; color: var(--slate-500); line-height: 1.4;">
                                Ingin mengganti akun Google atau beralih ke login email biasa?
                            </span>
                            <button type="submit" class="btn btn-danger-light" style="font-size: 0.825rem; padding: 8px 14px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                <span>Lepas Tautan</span>
                            </button>
                        </div>
                    </form>
                @else
                    {{-- Status Belum Tertaut --}}
                    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 20px;">
                        <p style="font-size: 0.875rem; color: var(--slate-700); margin: 0 0 10px 0; line-height: 1.5;">
                            Tautkan akun Google Anda sekarang agar Anda dapat login dengan lebih cepat, aman, dan tanpa repot mengetik password.
                        </p>
                        <ul style="font-size: 0.825rem; color: var(--slate-600); margin: 0; padding-left: 20px; line-height: 1.6;">
                            <li>Login 1-klik langsung dari halaman login admin.</li>
                            <li>Keamanan resmi berstandar Google OAuth 2.0.</li>
                            <li>Dapat dilepas sewaktu-waktu kapan saja.</li>
                        </ul>
                    </div>

                    @if ($isGoogleConfigured)
                        <a href="{{ route('auth.google', ['origin' => 'admin']) }}" class="btn" style="width: 100%; padding: 12px 20px; background-color: #ffffff; border: 1.5px solid #cbd5e1; border-radius: var(--radius-md); font-weight: 700; font-size: 0.92rem; color: #1e293b; box-shadow: 0 2px 4px rgba(0,0,0,0.06);">
                            <svg width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                            <span>Tautkan dengan Akun Google</span>
                        </a>
                    @else
                        <div style="background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 14px; margin-bottom: 14px;">
                            <div style="display: flex; gap: 10px; align-items: start;">
                                <span style="font-size: 1.25rem;">⚠️</span>
                                <div style="font-size: 0.825rem; color: #92400e; line-height: 1.5;">
                                    <strong>Kredensial Google OAuth Belum Terisi di .env</strong><br>
                                    Lengkapi <code>GOOGLE_CLIENT_ID</code> dan <code>GOOGLE_CLIENT_SECRET</code> di file konfigurasi.
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('auth.google') }}" class="btn btn-primary" style="width: 100%; padding: 12px 20px;">
                            <span>Buka Autentikasi Google</span>
                        </a>
                    @endif
                @endif
            </div>

            {{-- Cara Setup Google OAuth Guide (Disimpan rapi dalam Accordion agar tidak berantakan) --}}
            <details class="admin-help-accordion">
                <summary>
                    <span style="display: flex; align-items: center; gap: 8px;">
                        <span>💡</span>
                        <span>Panduan & Kredensial Pengembang (Google Console)</span>
                    </span>
                    <span style="font-size: 0.75rem; color: var(--slate-400);">Klik untuk buka/tutup ▼</span>
                </summary>
                <div class="admin-help-accordion-content">
                    <p style="margin: 0 0 10px 0; font-size: 0.825rem; color: var(--slate-600); line-height: 1.5;">
                        Informasi ini adalah panduan referensi teknis jika Anda ingin mengubah atau mendaftarkan kredensial OAuth di Google Cloud Console:
                    </p>
                    <ol style="padding-left: 20px; margin: 0 0 14px 0; line-height: 1.7; font-size: 0.825rem;">
                        <li>Buka <a href="https://console.cloud.google.com/apis/credentials" target="_blank" style="color: var(--primary); font-weight: 600; text-decoration: underline;">Google Cloud Console Credentials</a>.</li>
                        <li>Pilih Project, klik <strong>Create Credentials &gt; OAuth client ID</strong>.</li>
                        <li>Pilih Application Type: <strong>Web application</strong>.</li>
                        <li>Pada <strong>Authorized redirect URIs</strong>, masukkan URL berikut:</li>
                    </ol>
                    <div style="background: var(--slate-100); border: 1px solid var(--slate-200); border-radius: 8px; padding: 10px 12px; display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap;">
                        <code id="google_redirect_uri" style="font-size: 0.8rem; color: var(--slate-800); font-weight: 600; word-break: break-all;">
                            {{ config('services.google.redirect') }}
                        </code>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ config('services.google.redirect') }}'); alert('Authorized Redirect URI berhasil disalin!');" style="background: #ffffff; border: 1px solid var(--slate-300); border-radius: 6px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; cursor: pointer; color: var(--slate-700);">
                            Salin URL
                        </button>
                    </div>
                    <p style="margin: 12px 0 0 0; font-size: 0.78rem; color: var(--slate-500);">
                        Simpan <code>Client ID</code> & <code>Client Secret</code> ke file <code>.env</code> Anda.
                    </p>
                </div>
            </details>
        </div>

        {{-- Right Column: Informasi Profil & Ubah Password --}}
        <div>
            {{-- Form Edit Data Profil --}}
            <div class="admin-card" style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);">
                <div class="admin-card-header" style="margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1px solid var(--slate-100);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background-color: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <div>
                            <h2 class="admin-card-title" style="margin: 0; font-size: 1.1rem;">Informasi Akun</h2>
                            <p style="font-size: 0.8rem; color: var(--slate-500); margin: 2px 0 0 0;">Identitas akun dan alamat email administrator</p>
                        </div>
                    </div>
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
                            placeholder="Masukkan nama lengkap"
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
                            placeholder="nama@perusahaan.com"
                            required
                        >
                        <span class="form-help">Alamat email digunakan untuk login dan menerima notifikasi sistem.</span>
                    </div>

                    <div style="display: flex; justify-content: flex-end; margin-top: 24px;">
                        <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Form Ganti / Set Password --}}
            <div class="admin-card" style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);">
                <div class="admin-card-header" style="margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1px solid var(--slate-100);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background-color: #f1f5f9; color: var(--slate-700); display: flex; align-items: center; justify-content: center;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="admin-card-title" style="margin: 0; font-size: 1.1rem;">Keamanan Password</h2>
                            <p style="font-size: 0.8rem; color: var(--slate-500); margin: 2px 0 0 0;">
                                {{ $user->hasPassword() ? 'Ubah password login manual Anda secara berkala.' : 'Buat password manual untuk opsi login email & password.' }}
                            </p>
                        </div>
                    </div>
                </div>

                @if (!$user->hasPassword())
                    <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; display: flex; gap: 12px; align-items: flex-start;">
                        <span style="font-size: 1.25rem; line-height: 1;">💡</span>
                        <div style="font-size: 0.825rem; color: #1e40af; line-height: 1.5;">
                            <strong>Akun Terdaftar via Google SSO:</strong> Anda saat ini belum memiliki kata sandi manual. Buat kata sandi baru di bawah ini agar Anda memiliki opsi login ganda (bisa via Google ataupun via Email & Password).
                        </div>
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

                    <div style="display: flex; justify-content: flex-end; margin-top: 24px;">
                        <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <span>{{ $user->hasPassword() ? 'Perbarui Password' : 'Buat Password Akun' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
