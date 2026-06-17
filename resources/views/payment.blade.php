@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="container py-5" style="max-width: 960px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    {{-- Tombol Kembali ke Keranjang --}}
    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary mb-4 rounded-pill px-4 text-decoration-none">
        <i class="fas fa-arrow-left me-2"></i> Kembali ke Keranjang
    </a>

    {{-- Judul Halaman --}}
    <h2 class="fw-bold text-dark mb-4 text-uppercase tracking-wider fs-3">PEMBAYARAN</h2>

    {{-- Route diarahkan ke proses transaksi akhir kelompokmu --}}
    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
        @csrf

        <div class="row g-4">

            {{-- ── SISI KIRI: RINGKASAN BELANJA & FORM ALAMAT ── --}}
            <div class="col-lg-7 d-flex flex-column gap-4">

                {{-- Informasi Pembayaran / Ringkasan Produk --}}
                <div>
                    <h3 class="text-secondary fw-semibold fs-6 mb-3">Informasi Pembayaran</h3>

                    <div class="card border border-secondary-subtle rounded-3 overflow-hidden shadow-sm">
                        
                        {{-- Brand Label RUNORA --}}
                        <div class="card-header bg-white border-bottom border-secondary-subtle px-4 py-3">
                            <span class="fw-bold text-dark tracking-wide" style="font-size: 0.9rem;">RUNORA STORE</span>
                        </div>

                        {{-- Loop Daftar Produk Terpilih yang Lolos dari Session --}}
                        <div class="list-group list-group-flush border-0">
                            @php $grandTotal = 0; @endphp
                            @foreach ($checkoutItems as $cart)
                                @php 
                                    $subtotalItem = $cart->product->harga * $cart->kuantitas; 
                                    $grandTotal += $subtotalItem;
                                @endphp
                                <div class="list-group-item d-flex align-items-center gap-3 px-4 py-3 border-bottom border-light">
                                    {{-- Box Gambar --}}
                                    <div class="border border-secondary-subtle rounded-3 p-1 bg-white flex-shrink-0 d-flex align-items-center justify-content-center" 
                                         style="width: 75px; height: 75px; overflow: hidden;">
                                        <img src="{{ $cart->product->gambar ? asset('products/' . $cart->product->gambar) : asset('images/placeholder.png') }}"
                                             alt="{{ $cart->product->name }}"
                                             class="w-100 h-100 object-fit-cover rounded-2">
                                    </div>

                                    {{-- Info Nama & Jumlah Kuantitas Beli --}}
                                    <div class="flex-grow-1 min-w-0">
                                        <p class="fw-bold text-dark mb-1 text-truncate fs-6">{{ $cart->product->name }}</p>
                                        <p class="text-muted mb-0 small">{{ $cart->kuantitas }} x Rp. {{ number_format($cart->product->harga, 0, ',', '.') }}</p>
                                    </div>

                                    {{-- Total Harga Per Item --}}
                                    <div class="text-end fw-semibold text-dark fs-6 whitespace-nowrap">
                                        Rp. {{ number_format($subtotalItem, 0, ',', '.') }}
                                    </div>
                                    
                                    {{-- Input Hidden Otomatis untuk disetor ke CheckoutProcess --}}
                                    <input type="hidden" name="cart_ids[]" value="{{ $cart->id }}">
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

                {{-- Form Isian Data Pengiriman Alamat Pembeli --}}
                <div class="card border border-secondary-subtle rounded-3 shadow-sm p-4 bg-white">
                    <h3 class="text-secondary fw-semibold fs-6 mb-3 border-bottom pb-2">Alamat Pengiriman</h3>
                    
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label text-muted small fw-bold uppercase">Nama Penerima</label>
                            <input type="text" name="nama_penerima" class="form-control rounded-2 border-secondary-subtle" value="{{ Auth::user()->name }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted small fw-bold uppercase">Nomor Telepon / WA</label>
                            <input type="tel" name="telepon" class="form-control rounded-2 border-secondary-subtle" placeholder="Contoh: 0812xxxxxxxx" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-muted small fw-bold uppercase">Alamat Lengkap Rumah</label>
                            <textarea name="alamat" rows="3" class="form-control rounded-2 border-secondary-subtle" placeholder="Nama jalan, RT/RW, Kecamatan, Kabupaten/Kota" required></textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── SISI KANAN: RINGKASAN TOTAL & TOMBOL BAYAR ── --}}
            <div class="col-lg-5">
                <div class="card border border-secondary-subtle rounded-3 shadow-sm bg-white p-4 sticky-top" style="top: 100px; z-index: 10;">
                    <h3 class="text-secondary fw-semibold fs-6 mb-3 border-bottom pb-2">Ringkasan Pesanan</h3>
                    
                    {{-- Detail Biaya Perhitungan --}}
                    <div class="d-flex flex-column gap-2 mb-4" style="font-size: 0.9rem;">
                        <div class="d-flex justify-content-between text-muted">
                            <span>Subtotal Produk</span>
                            <span class="fw-semibold">Rp. {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted">
                            <span>Biaya Pengiriman (Ongkir)</span>
                            <span class="text-success fw-bold">Gratis Ongkir</span>
                        </div>
                        <hr class="text-secondary-subtle my-2">
                        <div class="d-flex justify-content-between align-items-center text-dark font-black">
                            <span class="fw-bold fs-5">Total Bayar:</span>
                            <span class="fw-bold text-danger fs-4">Rp. {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Tombol Konfirmasi Pembayaran Akhir --}}
                    <button type="submit" class="btn btn-danger btn-lg w-100 py-3 rounded-pill fw-bold uppercase tracking-wider shadow-sm fs-6">
                        <i class="fas fa-wallet me-2"></i> Konfirmasi Pembayaran
                    </button>
                    
                    <div class="text-center mt-3 text-muted" style="font-size: 0.75rem;">
                        <i class="fas fa-shield-alt text-success me-1"></i> Transaksi Aman & Terproteksi Sistem RUNORA Belanja
                    </div>
                </div>
            </div>

        </div>

    </form>
</div>
@endsection