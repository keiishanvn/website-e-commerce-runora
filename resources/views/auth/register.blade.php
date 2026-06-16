@extends('layouts.app')

@section('title', 'Daftar Akun Baru - RUNORA')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center" style="margin-top: -30px;">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="logo mb-2 fw-bold fs-2">
                            <span class="text-danger">RUN</span><span class="text-dark">ORA</span>
                        </h2>
                        <p class="text-muted small fw-medium">Buat Akun Baru Anda</p>
                    </div>
                    
                    <div id="alertMessage" class="alert d-none small" role="alert"></div>
                    
                    <form id="registerForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Nama</label>
                            <input type="text" class="form-control py-2.5 rounded-3 fs-6" id="regName" name="name" placeholder="Masukkan Nama Lengkap" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Email</label>
                            <input type="email" class="form-control py-2.5 rounded-3 fs-6" id="regEmail" name="email" placeholder="Masukkan Email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Password</label>
                            <input type="password" class="form-control py-2.5 rounded-3 fs-6" id="regPassword" name="password" placeholder="Masukkan Password" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small">No. Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 py-2.5 rounded-start-3 d-flex align-items-center gap-2">
                                    <img src="https://flagcdn.com/w20/id.png" alt="ID Flag" style="width: 20px; height: auto;">
                                    <span class="text-dark fw-medium small">+62</span>
                                </span>
                                <input type="text" class="form-control py-2.5 border-start-0 rounded-end-3 fs-6" id="regPhone" name="phone" placeholder="XXXXXXXX" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-danger w-100 py-2.5 rounded-3 fw-bold shadow-sm text-white" id="registerBtn">
                            Daftar
                        </button>
                    </form>
                    
                    <p class="text-center mt-4 mb-0 small text-muted">
                        Sudah punya akun? <a href="{{ route('login') }}" class="text-danger fw-semibold text-decoration-none">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const registerBtn = document.getElementById('registerBtn');
        const originalText = registerBtn.innerHTML;
        
        // Ambil data form
        const formData = new FormData(this);
        
        // Animasi Loading
        registerBtn.disabled = true;
        registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mendaftarkan...';
        
        try {
            const response = await fetch('{{ route("register") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                // Tampilkan sukses, lalu lempar ke halaman login
                showAlert('Registrasi berhasil! Mengalihkan ke halaman login...', 'success');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 2000);
            } else {
                // Tangani error validasi (misal email sudah terdaftar)
                let errorMsg = data.message;
                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat().join('<br>');
                }
                showAlert(errorMsg || 'Registrasi gagal. Coba lagi.', 'danger');
                registerBtn.disabled = false;
                registerBtn.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan sistem. Silakan coba lagi.', 'danger');
            registerBtn.disabled = false;
            registerBtn.innerHTML = originalText;
        }
    });
    
    function showAlert(message, type) {
        const alertDiv = document.getElementById('alertMessage');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show shadow-sm`;
        alertDiv.innerHTML = `<div>${message}</div>`;
        alertDiv.classList.remove('d-none');
    }
</script>
@endpush