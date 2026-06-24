@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-4" style="max-width: 1040px; font-family: 'Inter', -apple-system, sans-serif;">
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="text-dark text-decoration-none fs-4">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>
    <h2 class="fw-black text-dark mb-4 tracking-tight fs-3 text-uppercase">PEMBAYARAN</h2>

    // Formulir Checkout Utama
    <form action="{{ route('checkout.process') }}" method="POST" id="main-payment-form">
        @csrf

        <div class="row g-4 items-start">

            // ── SEKTOR KIRI: INFORMASI PEMBAYARAN & DAFTAR ITEM BELANJA ──
            <div class="col-lg-7">
                <h3 class="fw-bold text-dark fs-5 mb-3">Informasi Pembayaran</h3>

                <div class="card border border-light-subtle bg-white rounded-3 overflow-hidden shadow-sm">
                    <div class="card-header bg-white border-bottom border-gray-200 px-4 py-3">
                        <span class="fw-extrabold text-dark tracking-wider" style="font-size: 0.85rem;">RUNORA</span>
                    </div>

                    @php 
                        $totalHargaProduk = 0; 
                        $totalKuantitasItem = 0;
                    @endphp

                    <div class="list-group list-group-flush">
                        @foreach ($checkoutItems as $cart)
                            @php
                                $kuantitasBeli = is_object($cart) ? ($cart->kuantitas ?? 1) : ($cart['kuantitas'] ?? 1);
                                $cartId        = is_object($cart) ? ($cart->id ?? 0) : ($cart['id'] ?? 0);
                                $rawProduct    = is_object($cart) ? ($cart->product ?? null) : ($cart['product'] ?? null);

                                if (is_object($rawProduct)) {
                                    $namaProduk     = $rawProduct->name ?? 'Produk';
                                    $hargaProduk    = (float)($rawProduct->harga ?? 0);
                                    $gambarProduk   = $rawProduct->gambar ?? null;
                                } else {
                                    $namaProduk     = $rawProduct['name'] ?? 'Produk';
                                    $hargaProduk    = (float)($rawProduct['harga'] ?? 0);
                                    $gambarProduk   = $rawProduct['gambar'] ?? null;
                                }

                                $subtotalItem        = $hargaProduk * $kuantitasBeli;
                                $totalHargaProduk   += $subtotalItem;
                                $totalKuantitasItem += $kuantitasBeli;
                            @endphp

                            <div class="list-group-item d-flex align-items-center gap-3 px-4 py-3 border-bottom border-light">
                                <div class="border border-secondary-subtle rounded-3 p-1 bg-white flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; overflow: hidden;">
                                    <img src="{{ $gambarProduk ? asset('products/' . $gambarProduk) : asset('images/placeholder.png') }}" alt="{{ $namaProduk }}" class="w-100 h-100 object-fit-cover rounded-2">
                                </div>

                                <div class="flex-grow-1 min-w-0">
                                    <p class="fw-bold text-dark mb-0 fs-6 truncate">{{ $namaProduk }}</p>
                                    <p class="text-muted mb-0 small" style="font-size: 0.75rem;">Variasi</p>
                                    <p class="text-muted mb-0 small" style="font-size: 0.75rem;">Ukuran</p>
                                </div>

                                <div class="text-secondary px-2 fw-semibold bg-light border rounded" style="font-size: 0.8rem; padding-top: 2px; padding-bottom: 2px;">
                                    {{ $kuantitasBeli }}x
                                </div>

                                <div class="text-end fw-bold text-dark ps-2 whitespace-nowrap" style="width: 110px; font-size: 0.85rem;">
                                    Rp. {{ number_format($subtotalItem, 0, ',', '.') }}
                                </div>

                                <input type="hidden" name="cart_ids[]" value="{{ $cartId }}">
                            </div>
                        @endforeach
                    </div>

                    // Pilihan Metode Pembayaran
                    <div class="card-body px-4 py-3 bg-white border-top border-light-subtle">
                        <p class="fw-bold text-dark mb-3" style="font-size: 0.85rem; letter-spacing: 0.3px;">Metode Pembayaran</p>
                        <div class="d-flex flex-column gap-3">
                            <label class="d-flex align-items-center justify-content-between cursor-pointer w-100 py-1">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="d-flex align-items-center justify-content-center rounded-full bg-orange-500 text-white fw-black text-center" style="width: 22px; height: 22px; font-size: 10px;">S</span>
                                    <span class="text-secondary-dark fw-medium" style="font-size: 0.85rem;">SeaBank</span>
                                </div>
                                <input type="radio" name="metode_pembayaran" value="seabank" class="form-check-input border-2 border-secondary m-0" style="width: 18px; height: 18px; accent-color: #b91c1c;" checked required>
                            </label>

                            <label class="d-flex align-items-center justify-content-between cursor-pointer w-100 py-1">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="text-secondary d-flex align-items-center justify-content-center" style="width: 22px;"><i class="fas fa-university fs-6"></i></span>
                                    <span class="text-secondary-dark fw-medium" style="font-size: 0.85rem;">Transfer Bank</span>
                                </div>
                                <input type="radio" name="metode_pembayaran" value="transfer_bank" class="form-check-input border-2 border-secondary m-0" style="width: 18px; height: 18px; accent-color: #b91c1c;" required>
                            </label>

                            <label class="d-flex align-items-center justify-content-between cursor-pointer w-100 py-1">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="bg-dark text-white rounded-1 fw-bold tracking-tighter text-center d-flex align-items-center justify-content-center" style="width: 24px; height: 16px; font-size: 7px;">QRIS</span>
                                    <span class="text-secondary-dark fw-medium" style="font-size: 0.85rem;">QRIS</span>
                                </div>
                                <input type="radio" name="metode_pembayaran" value="qris" class="form-check-input border-2 border-secondary m-0" style="width: 18px; height: 18px; accent-color: #b91c1c;" required>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            // SEKTOR KANAN: RINGKASAN PESANAN & ALAMAT PENGIRIMAN 
            <div class="col-lg-5">
                <h3 class="fw-bold text-dark fs-5 mb-0">Ringkasan Pesanan</h3>
                <p class="text-muted mb-3" style="font-size: 0.75rem;">Dijual dan dikirim oleh <span class="fw-bold text-secondary-dark">RUNORA</span></p>

                <div class="card border border-light-subtle rounded-3 p-4 bg-white shadow-sm mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="fw-bold text-dark" style="font-size: 0.85rem;">Alamat Pengiriman</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span id="label-alamat-display" class="fw-bold text-dark bg-light px-2 py-0.5 border rounded-2" style="font-size: 0.75rem;">Rumah</span>
                        <button type="button" class="btn btn-sm btn-outline-danger fw-bold border-1 rounded px-2 py-0.5" style="font-size: 9px; letter-spacing: 0.3px;" data-bs-toggle="modal" data-bs-target="#editAlamatModal">EDIT</button>
                    </div>
                    <p id="nama-penerima-display" class="fw-bold text-dark mb-1" style="font-size: 0.8rem;">{{ Auth::user()->name }}</p>
                    <p id="alamat-lengkap-display" class="text-secondary lh-relaxed mb-0" style="font-size: 0.75rem;">Jalan Rumah xxxx, Kabupaten, Kelurahan, Provinsi, Kode Pos</p>

                    <input type="hidden" name="label_alamat" id="hidden-label" value="Rumah">
                    <input type="hidden" name="nama_penerima" id="hidden-nama" value="{{ Auth::user()->name }}">
                    <input type="hidden" name="alamat_pengiriman" id="hidden-alamat-full" value="Jalan Rumah xxxx, Kabupaten, Kelurahan, Provinsi, Kode Pos">
                </div>

                <div class="card border border-light-subtle rounded-3 p-4 bg-white shadow-sm">
                    <div class="d-flex flex-column gap-2" style="font-size: 0.8rem;">
                        <div class="d-flex justify-content-between text-muted"><span>Jumlah Item</span><span class="fw-semibold text-dark">{{ $totalKuantitasItem }} item</span></div>
                        <div class="d-flex justify-content-between text-muted"><span>Subtotal</span><span class="fw-semibold text-dark">Rp. {{ number_format($totalHargaProduk, 0, ',', '.') }}</span></div>
                        <div class="d-flex justify-content-between text-muted"><span>Diskon</span><span class="fw-semibold text-dark">-Rp. 0</span></div>
                        <div class="d-flex justify-content-between text-muted"><span>Pengiriman</span><span class="fw-semibold text-dark">Rp. 0</span></div>
                        <hr class="text-secondary-subtle my-2">
                        <div class="d-flex justify-content-between align-items-center"><span class="fw-bold text-dark fs-6">Total</span><span class="fw-bold text-dark fs-6">Rp. {{ number_format($totalHargaProduk, 0, ',', '.') }}</span></div>
                    </div>
                </div>
            </div>

            // Button Checkout Utama
            <div class="col-12 mt-4">
                <button type="submit" id="btn-submit-checkout" class="btn btn-white border border-danger text-danger w-100 py-3 rounded-2 fw-bold tracking-widest text-uppercase shadow-sm fs-6 btn-checkout-runora">
                    CHECKOUT
                </button>
            </div>
        </div>
    </form>
</div>

// Modal Edit Alamat 
<div class="modal fade" id="editAlamatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 580px;">
        <div class="modal-content border-0 rounded-3 shadow-lg px-3 py-2">
            <div class="modal-header border-0 pb-0">
                <h4 class="modal-title fw-extrabold text-dark tracking-tight fs-4">EDIT ALAMAT</h4>
                <button type="button" class="btn-close fs-6" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark mb-1" style="font-size: 0.8rem;">Label Alamat</label>
                        <input type="text" id="modal-label" class="form-control text-xs" value="Rumah" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark mb-1" style="font-size: 0.8rem;">Negara</label>
                        <input type="text" class="form-control text-xs bg-light" value="Indonesia" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark mb-0" style="font-size: 0.8rem;">Nama Depan</label>
                        <input type="text" id="modal-nama-depan" class="form-control text-xs" value="{{ Auth::user()->name }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark mb-0" style="font-size: 0.8rem;">Nama Belakang</label>
                        <input type="text" id="modal-nama-belakang" class="form-control text-xs">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold text-dark mb-0" style="font-size: 0.8rem;">Alamat</label>
                        <input type="text" id="modal-jalan" class="form-control text-xs" value="Jalan Rumah xxxx" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark mb-1" style="font-size: 0.8rem;">Provinsi</label>
                        <select id="modal-provinsi" class="form-select text-xs"><option value="Jawa Barat">Jawa Barat</option></select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark mb-1" style="font-size: 0.8rem;">Kota/Kabupaten</label>
                        <select id="modal-kota" class="form-select text-xs"><option value="Bogor">Bogor</option></select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark mb-1" style="font-size: 0.8rem;">Kecamatan</label>
                        <select id="modal-kecamatan" class="form-select text-xs"><option value="Ciomas">Ciomas</option></select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark mb-1" style="font-size: 0.8rem;">Kelurahan</label>
                        <select id="modal-kelurahan" class="form-select text-xs"><option value="Padasuka">Padasuka</option></select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark mb-0" style="font-size: 0.8rem;">Kode Pos</label>
                        <input type="text" id="modal-kodepos" class="form-control text-xs" value="16610" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark mb-0" style="font-size: 0.8rem;">No Telepon</label>
                        <input type="tel" id="modal-telepon" class="form-control text-xs" value="08123456789" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-2 d-flex justify-content-start">
                <button type="button" class="btn btn-outline-danger fw-bold rounded-2 px-4 py-2" style="font-size: 0.85rem;" id="btn-simpan-alamat">Simpan Alamat</button>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-checkout-runora { transition: all 0.2s ease-in-out; }
    .btn-checkout-runora:hover { background-color: #dc3545 !important; color: #ffffff !important; }
    .text-xs { font-size: 0.75rem !important; }
</style>

@push('scripts')
<script>
    // Handler Modal Edit Alamat
    document.getElementById('btn-simpan-alamat').addEventListener('click', function() {
        const label = document.getElementById('modal-label').value;
        const namaDepan = document.getElementById('modal-nama-depan').value;
        const namaBelak = document.getElementById('modal-nama-belakang').value;
        const jalan = document.getElementById('modal-jalan').value;
        const provinsi = document.getElementById('modal-provinsi').value;
        const kota = document.getElementById('modal-kota').value;
        const kecamatan = document.getElementById('modal-kecamatan').value;
        const kelurahan = document.getElementById('modal-kelurahan').value;
        const kodepos = document.getElementById('modal-kodepos').value;
        
        const namaLengkap = namaDepan + (namaBelak ? ' ' + namaBelak : '');
        const alamatFull  = `${jalan}, ${kelurahan}, ${kecamatan}, ${kota}, ${provinsi}, ${kodepos}`;

        document.getElementById('label-alamat-display').textContent = label;
        document.getElementById('nama-penerima-display').textContent = namaLengkap;
        document.getElementById('alamat-lengkap-display').textContent = alamatFull;

        document.getElementById('hidden-label').value = label;
        document.getElementById('hidden-nama').value = namaLengkap;
        document.getElementById('hidden-alamat-full').value = alamatFull;

        bootstrap.Modal.getInstance(document.getElementById('editAlamatModal')).hide();
    });

    // Kirim form (Mencegah Kehilangan Session & Bug Refresh)
    document.getElementById('main-payment-form').addEventListener('submit', function(e) {
        e.preventDefault(); 

        const btnCheckout = document.getElementById('btn-submit-checkout');
        btnCheckout.disabled = true;
        btnCheckout.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>MEMPROSES...';

        // Ambil data inputan form
        const cartIds = Array.from(document.querySelectorAll('input[name="cart_ids[]"]')).map(el => el.value);
        const metodePembayaran = document.querySelector('input[name="metode_pembayaran"]:checked').value;
        const alamatPengiriman = document.getElementById('hidden-alamat-full').value;
        const namaPenerima = document.getElementById('hidden-nama').value;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Kirim data langsung ke backend laravel via background request
        fetch("{{ route('checkout.process') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token,
                "Accept": "application/json"
            },
            body: JSON.stringify({
                cart_ids: cartIds,
                metode_pembayaran: metodePembayaran,
                alamat_pengiriman: alamatPengiriman,
                nama_penerima: namaPenerima
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Tampilkan pop-up KETIKA data sudah sukses masuk database MySQL
                Swal.fire({
                    title: 'Pembelian Berhasil!',
                    text: 'Terima kasih, pesanan Anda telah disimpan ke riwayat pembelian.',
                    icon: 'success',
                    confirmButtonColor: '#b91c1c', 
                    confirmButtonText: 'OK',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Bersihkan total angka badge keranjang belanja di navbar secara real-time
                        const navBadge = document.getElementById('cartCount');
                        if(navBadge) navBadge.innerText = '0';
                        
                        // Lanjut ke riwayat pesanan riil
                        window.location.href = "{{ route('riwayat.pesanan') }}"; 
                    }
                });
            } else {
                alert(data.message || 'Terjadi kesalahan sistem, silakan coba lagi.');
                btnCheckout.disabled = false;
                btnCheckout.innerHTML = 'CHECKOUT';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memproses pesanan. Pastikan session belanja kamu masih aktif!');
            btnCheckout.disabled = false;
            btnCheckout.innerHTML = 'CHECKOUT';
        });
    });
</script>
@endpush
@endsection