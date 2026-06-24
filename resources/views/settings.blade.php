@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="container-fluid px-0" style="font-family: 'Inter', -apple-system, sans-serif;">
    <div class="row g-0 d-flex align-items-stretch" style="min-height: calc(100vh - 70px);">
        
        {{-- 1. SIDEBAR KIRI --}}
        <div class="col-md-3 bg-white d-flex flex-column justify-content-between shadow-sm" style="max-width: 280px; border-right: 4px solid #dee2e6 !important;">
            <div>
                <div class="d-flex align-items-center gap-3 px-4 py-4" style="background-color: #b91c1c;">
                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center overflow-hidden" style="width: 55px; height: 55px; flex-shrink: 0;">
                        <i class="fas fa-user text-dark fs-3"></i>
                    </div>
                    <div class="min-w-0 text-white">
                        <h6 class="fw-bold mb-0 text-truncate" style="font-size: 0.95rem; letter-spacing: 0.2px;">{{ Auth::user()->name }}</h6>
                        <p class="mb-0 small text-truncate opacity-85" style="font-size: 0.8rem;">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                {{-- Navigasi Menu Vertikal --}}
                <div class="nav flex-column border-bottom border-secondary-subtle">
                    <a href="{{ route('riwayat.pesanan') }}" class="nav-link text-dark fw-bold px-4 py-3 d-flex align-items-center gap-3 text-decoration-none border-bottom border-secondary-subtle bg-white btn-menu-pasif" style="font-size: 0.85rem;">
                        <i class="fas fa-history fs-5"></i> Riwayat Pesanan
                    </a>
                    <a href="{{ route('user.settings') }}" class="nav-link text-dark fw-bold px-4 py-3 d-flex align-items-center gap-3 text-decoration-none bg-light" style="font-size: 0.85rem;">
                        <i class="far fa-user fs-5"></i> Pengaturan Akun
                    </a>
                </div>
            </div>

            <div class="px-4 py-4 my-4">
                <form action="{{ route('logout') }}" method="POST" class="w-100">
                    @csrf
                    <button type="submit" class="btn btn-outline-dark bg-white text-dark rounded-3 fw-bold px-4 py-2 shadow-sm btn-logout-outline w-100" style="font-size: 0.85rem; border: 1px solid #212529;">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        {{-- 2. KONTEN KANAN: FORM PENGATURAN AKUN --}}
        <div class="col-md-9 flex-grow-1 bg-white px-5 py-5">
            
            <h2 class="fw-black text-dark text-uppercase tracking-tight mb-5 fs-4" style="letter-spacing: 0.3px;">PENGATURAN AKUN</h2>

            @if (session('success'))
                <div class="alert alert-success rounded-3 mb-4 small fw-bold" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="row g-4">
                {{-- SEKTOR KIRI: FORM PENGATURAN PROFIL --}}
                <div class="col-lg-7">
                    <form action="{{ route('user.settings.update') }}" method="POST">
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
                        <p class="fw-bold text-secondary mb-3" style="font-size: 0.75rem; letter-spacing: 0.3px;">UBAH PASSWORD (KOSONGKAN JIKA TIDAK INGIN DIUBAH)</p>

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

                {{-- SEKTOR KANAN: MENGISI AREA KOSONG DENGAN CARD INFORMASI --}}
                <div class="col-lg-5 ps-lg-4">
                    {{-- Card 1: Status Keamanan Akun --}}
                    <div class="card border-0 bg-light rounded-4 p-4 mb-3 shadow-sm">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-danger text-white rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-shield-alt fs-5"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Keamanan Akun</h6>
                        </div>
                        <p class="text-secondary small mb-2" style="text-align: justify; line-height: 1.4;">
                            Pastikan password Anda kuat dengan kombinasi huruf kapital, angka, dan simbol unik demi menjaga keamanan akun RUNORA Anda dari aktivitas mencurigakan.
                        </p>
                        <span class="badge bg-success-subtle text-success align-self-start rounded-pill px-3 py-1.5 mt-2" style="font-size: 0.75rem;">
                            <i class="fas fa-check me-1"></i> Akun Terverifikasi
                        </span>
                    </div>

                    {{-- Card 2: Log Informasi Sesi Pengguna --}}
                    <div class="card border-0 bg-light rounded-4 p-4 shadow-sm">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-dark text-white rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-info-circle fs-5"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Informasi Sesi</h6>
                        </div>
                        <div class="small text-secondary">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Peran Pengguna:</span>
                                <span class="fw-bold text-dark text-capitalize">{{ Auth::user()->role ?? 'Pembeli' }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Terdaftar Sejak:</span>
                                <span class="fw-bold text-dark">{{ Auth::user()->created_at ? Auth::user()->created_at->format('d M Y') : date('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<style>
    .hover-sidebar-menu:hover { background-color: rgba(255, 255, 255, 0.08); opacity: 1 !important; }
    .btn-logout-outline { transition: all 0.2s ease; }
    .btn-logout-outline:hover { background-color: #212529 !important; color: #ffffff !important; }
    .btn-save-profile { transition: all 0.2s ease-in-out; border-width: 2px !important; }
    .btn-save-profile:hover { background-color: #b91c1c !important; color: #ffffff !important; }
    .fs-7 { font-size: 0.8rem !important; }
</style>
@endsection