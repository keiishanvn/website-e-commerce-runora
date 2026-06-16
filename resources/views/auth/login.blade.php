@extends('layouts.app')

@section('title', 'Login - RUNORA')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center" style="margin-top: -50px;">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="logo mb-2 fw-bold fs-2">
                            <span class="text-danger">RUN</span><span class="text-dark">ORA</span>
                        </h2>
                        <p class="text-muted small fw-medium">Masuk ke Akun Anda</p>
                    </div>
                    
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show small" role="alert">
                        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif
                    
                    <div id="alertMessage" class="alert d-none small" role="alert"></div>
                    
                    <form id="loginForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Email</label>
                            <input type="email" class="form-control py-2.5 rounded-3 fs-6" id="loginEmail" name="email" placeholder="Masukkan Email" required autocomplete="username">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small">Password</label>
                            <input type="password" class="form-control py-2.5 rounded-3 fs-6" id="loginPassword" name="password" placeholder="Masukkan Password" required autocomplete="current-password">
                        </div>
                        
                        <button type="submit" class="btn btn-danger w-100 py-2.5 rounded-3 fw-bold shadow-sm text-white transition" id="loginBtn">
                            Masuk
                        </button>
                    </form>
                    
                    <p class="text-center mt-4 mb-0 small text-muted">
                        Belum punya akun? <a href="{{ route('register') }}" class="text-danger fw-semibold text-decoration-none">Daftar di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;
        const loginBtn = document.getElementById('loginBtn');
        const originalText = loginBtn.innerHTML;
        
        const formData = new FormData();
        formData.append('email', email);
        formData.append('password', password);
        
        loginBtn.disabled = true;
        loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        
        try {
            const response = await fetch('{{ route("login") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            // 1. JIKA PASSING / SUKSES (Status 200)
            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    window.location.href = data.redirect;
                    return;
                }
            } 
            
            // 2. JIKA VALIDASI SALAH / PASSWORD SALAH (Status 422)
            if (response.status === 422) {
                const data = await response.json();
                let pesanError = data.message;
                
                if (data.errors) {
                    // Menggabungkan pesan error seperti "The password field is incorrect"
                    pesanError = Object.values(data.errors).flat().join('<br>');
                }
                
                showAlert(pesanError || 'Email atau password yang Anda masukkan salah!');
            } else {
                // Jika terkena error status lain (misal 500 atau 404)
                showAlert('Gagal merespons server. Periksa kembali data akun.', 'danger');
            }

            // Kembalikan tombol ke semula jika gagal login
            loginBtn.disabled = false;
            loginBtn.innerHTML = originalText;

        } catch (error) {
            console.error('Detail Error:', error);
            // Ini baru benar-benar dipanggil kalau jaringan putus atau server mati total
            showAlert('Email atau password salah! (Sistem menolak akses)', 'danger');
            loginBtn.disabled = false;
            loginBtn.innerHTML = originalText;
        }
    });
    
    function showAlert(message, type) {
        const alertDiv = document.getElementById('alertMessage');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show shadow-sm`;
        alertDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2 fs-5"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        alertDiv.classList.remove('d-none');
    }
</script>
@endpush