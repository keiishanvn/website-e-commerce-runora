@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="container-fluid px-0" style="font-family: 'Inter', -apple-system, sans-serif; min-height: 80vh;">
    
    <div class="row g-0">
        
        {{-- ── 1. SIDEBAR KIRI (SAMA DENGAN RIWAYAT, KECUALI STATUS AKTIFNYA) ── --}}
        <div class="col-md-3 bg-red-800 text-white min-vh-100 d-flex flex-column justify-content-between shadow-sm" style="background-color: #b91c1c; max-width: 280px;">
            <div>
                <div class="d-flex align-items-center gap-3 px-4 py-4 border-bottom border-danger" style="border-color: rgba(255,255,255,0.1) !important;">
                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center border border-2 border-white overflow-hidden" style="width: 50px; height: 50px; flex-shrink: 0;">
                        <i class="fas fa-user text-dark fs-4"></i>
                    </div>
                    <div class="min-w-0">
                        <h6 class="fw-bold mb-0 text-truncate" style="font-size: 0.9rem;">{{ Auth::user()->name }}</h6>
                        <p class="text-white-50 mb-0 small text-truncate" style="font-size: 0.75rem;">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <div class="nav flex-column mt-3">
                    {{-- Menu Riwayat Pesanan (Standby Merah) --}}
                    <a href="{{ route('riwayat.pesanan') }}" class="nav-link text-white fw-medium px-4 py-3 d-flex align-items-center gap-3 text-decoration-none opacity-75 hover-sidebar-menu" style="font-size: 0.85rem;">
                        <i class="fas fa-history fs-5"></i> Riwayat Pesanan
                    </a>
                    
                    {{-- Menu Pengaturan Akun (SEKARANG JADI AKTIF / TERSELEKSI PUTIH) --}}
                    <a href="{{ route('user.settings') }}" class="nav-link text-dark fw-bold bg-white px-4 py-3 d-flex align-items-center gap-3 text-decoration-none" style="font-size: 0.85rem;">
                        <i class="fas fa-user-cog fs-5"></i> Pengaturan Akun
                    </a>
                </div>
            </div>

            <div class="px-4 py-4 border-top border-danger" style="border-color: rgba(255,255,255,0.1) !important;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-white bg-white text-dark border border-dark rounded-2 fw-bold w-100 py-2 text-uppercase tracking-wider shadow-sm btn-logout-runora" style="font-size: 0.75rem;">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        {{-- ── 2. KONTEN SEBELAH KANAN: FORM ISIAN PENGATURAN PROFIL AKUN ── --}}
        <div class="col-md-9 flex-grow-1 bg-white px-5 py-5">
            
            <h2 class="fw-black text-dark text-uppercase tracking-tight mb-5 fs-4" style="letter-spacing: 0.3px;">PENGATURAN AKUN</h2>

            @if (session('success'))
                <div class="alert alert-success rounded-3 mb-4 small fw-bold" role="alert" style="max-width: 550px;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Formulir Data Pengguna Minimalis Estetik --}}
            <form action="{{ route('user.settings.update') }}" method="POST" style="max-width: 550px;">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size: 0.8rem;">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control border-secondary-subtle rounded py-2 px-3 bg-white text-dark fs-7" value="{{ Auth::user()->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size: 0.8rem;">Alamat Email</label>
                    <input type="email" name="email" class="form-control border-secondary-subtle rounded py-2 px-3 bg-white text-dark fs-7" value="{{ Auth::user()->email }}" required>
                </div>

                <hr class="text-secondary-subtle my-4">
                <p class="fw-bold text-secondary mb-3" style="font-size: 0.8rem; letter-spacing: 0.3px;">UBAH PASSWORD (KOSONGKAN JIKA TIDAK INGIN DIUBAH)</p>

                <div class="mb-3">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size: 0.8rem;">Password Baru</label>
                    <input type="password" name="password" class="form-control border-secondary-subtle rounded py-2 px-3 bg-white text-dark fs-7" placeholder="Minimal 8 karakter">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size: 0.8rem;">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control border-secondary-subtle rounded py-2 px-3 bg-white text-dark fs-7" placeholder="Ulangi password baru">
                </div>

                <button type="submit" class="btn btn-white border border-danger text-danger fw-bold rounded-2 px-4 py-2.5 text-uppercase tracking-wider btn-save-profile" style="font-size: 0.75rem;">
                    Simpan Perubahan
                </button>
            </form>

        </div>

    </div>
</div>

<style>
    .hover-sidebar-menu:hover { background-color: rgba(255, 255, 255, 0.08); opacity: 1 !important; }
    .btn-logout-runora { transition: all 0.2s ease; }
    .btn-logout-runora:hover { background-color: #212529 !important; color: #ffffff !important; }
    .btn-save-profile { transition: all 0.2s ease-in-out; }
    .btn-save-profile:hover { background-color: #b91c1c !important; color: #ffffff !important; }
    .fs-7 { font-size: 0.8rem !important; }
</style>
@endsection