@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary mb-4 rounded-pill px-4 text-decoration-none">
        <i class="fas fa-arrow-left me-2"></i> Kembali ke Katalog
    </a>

    <div class="row">
        {{-- ── KIRI: AREA MEDIA / GAMBAR PRODUK ── --}}
        <div class="col-md-6">
            {{-- Box Gambar Utama (Hero Image) --}}
            <div class="position-relative border rounded-4 overflow-hidden shadow-sm bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                @if($product->gambar)
                    <img id="mainProductImage" src="{{ asset('products/' . $product->gambar) }}" alt="{{ $product->name }}" class="img-fluid w-100 h-100 object-fit-cover">
                @else
                    <div class="text-center p-4 text-muted">
                        <i class="fas fa-shoe-prints fa-4x mb-3 text-danger"></i>
                        <h5>[ Gambar {{ $product->name }} ]</h5>
                    </div>
                @endif
                
                @if($product->diskon > 0)
                    <span class="position-absolute top-0 start-0 badge bg-danger m-3 fs-6 px-3 py-2 rounded-pill shadow-sm">
                        Hemat {{ $product->diskon }}%
                    </span>
                @endif
            </div>

            {{-- AREA MENAMPILKAN GAMBAR DETAIL TAMBAHAN (THUMBNAILS) --}}
            @php
                $gambarDetailList = DB::table('product_images')->where('product_id', $product->id)->get();
            @endphp

            @if($gambarDetailList->count() > 0)
                <div class="row g-2 mt-2">
                    {{-- Kotak Gambar Utama Versi Kecil --}}
                    <div class="col-3">
                        <div class="border rounded-3 overflow-hidden bg-light cursor-pointer thumbnail-box active-thumbnail" style="height: 80px;" onclick="changeMainImage('{{ asset('products/' . $product->gambar) }}', this)">
                            <img src="{{ asset('products/' . $product->gambar) }}" class="w-100 h-100 object-fit-cover">
                        </div>
                    </div>
                    
                    {{-- Loop Semua Gambar Detail --}}
                    @foreach($gambarDetailList as $detail)
                        <div class="col-3">
                            <div class="border rounded-3 overflow-hidden bg-light cursor-pointer thumbnail-box" style="height: 80px;" onclick="changeMainImage('{{ asset('products/' . $detail->gambar_detail) }}', this)">
                                <img src="{{ asset('products/' . $detail->gambar_detail) }}" class="w-100 h-100 object-fit-cover">
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        {{-- ── KANAN: DETAIL INFORMASI & TOMBOL BELI (BOOTSTRAP MURNI) ── --}}
        <div class="col-md-6">
            <h1 class="fw-bold mb-2 text-dark">{{ $product->name }}</h1>
            <span class="badge bg-danger rounded-pill px-3 py-2 mb-3">{{ $product->kategori ?? 'Umum' }}</span>
            
            @php
                $hargaAsli = (float)$product->harga;
                $persenDiskon = (float)$product->diskon;
                $hargaSetelahDiskon = $hargaAsli - ($hargaAsli * $persenDiskon / 100);
            @endphp

            {{-- MANAGEMENT TAMPILAN HARGA CORET --}}
            @if($persenDiskon > 0)
                <div class="mb-4 p-3 bg-light rounded-3">
                    <span class="text-decoration-line-through text-muted me-2 fs-5">
                        Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                    </span>
                    <span class="text-danger fw-bold fs-2 d-block d-sm-inline">
                        Rp {{ number_format($hargaSetelahDiskon, 0, ',', '.') }}
                    </span>
                </div>
            @else
                <div class="mb-4 p-3 bg-light rounded-3">
                    <h2 class="text-dark fw-bold mb-0">Rp {{ number_format($hargaAsli, 0, ',', '.') }}</h2>
                </div>
            @endif
            
            {{-- MANAJEMEN STOK --}}
            <div class="mb-4">
                @if($product->stok > 0)
                    <span class="badge bg-success fs-6 px-3 py-2 rounded-pill">
                        <i class="fas fa-box me-1"></i> Stok: {{ $product->stok }} items tersedia
                    </span>
                @else
                    <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill">
                        <i class="fas fa-exclamation-circle me-1"></i> Stok Habis
                    </span>
                @endif
            </div>
            
            {{-- DESKRIPSI --}}
            <div class="mb-4 border-top pt-3">
                <label class="fw-bold text-dark mb-2">Deskripsi Produk:</label>
                <p class="text-secondary lh-base" style="text-align: justify;">{{ $product->deskripsi ?? 'Belum ada deskripsi untuk produk ini.' }}</p>
            </div>

            {{-- FORM AJAX SINKRONISASI (SISTEM BOOTSTRAP TANPA SIZE) --}}
            <form id="add-to-cart-form" class="mt-4 border-top pt-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                {{-- Input Jumlah Beli --}}
                <div class="mb-4">
                    <label class="fw-bold text-dark mb-2">Jumlah Beli:</label>
                    <div class="d-flex align-items-center gap-2">
                        {{-- Tombol Minus --}}
                        <button class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                style="width: 40px; height: 40px; padding: 0;"
                                id="decreaseQty" type="button" onclick="changeQuantity(-1)" {{ $product->stok <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-minus"></i>
                        </button>
                        
                        {{-- Kolom Angka (id dan name disamakan jadi kuantitas) --}}
                        <input type="number" id="display-qty" name="kuantitas" 
                               value="{{ $product->stok > 0 ? 1 : 0 }}" 
                               min="{{ $product->stok > 0 ? 1 : 0 }}" 
                               max="{{ $product->stok }}" 
                               style="width: 70px; height: 40px; text-align: center;" 
                               class="form-control text-center fw-bold bg-white" readonly
                               {{ $product->stok <= 0 ? 'disabled' : '' }}>
                        
                        {{-- Tombol Plus --}}
                        <button class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                style="width: 40px; height: 40px; padding: 0;"
                                id="increaseQty" type="button" onclick="changeQuantity(1)" {{ $product->stok <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                
                {{-- Baris Tombol Utama --}}
                <div class="d-flex gap-2 w-100 mt-4">
                    <button type="submit" class="btn btn-danger btn-lg flex-grow-1 py-3 rounded-pill fw-bold shadow-sm" 
                            id="addToCartBtn" {{ $product->stok <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart me-2"></i> Tambah ke Keranjang
                    </button>

                    <a href="{{ route('checkout.index') }}" class="btn btn-outline-secondary btn-lg py-3 px-4 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center text-decoration-none">
                        <i class="fas fa-store me-2"></i> Lanjut Belanja
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .thumbnail-box {
        transition: all 0.2s;
        border: 2px solid transparent !important;
    }
    .thumbnail-box:hover {
        border-color: #ddd !important;
    }
    .active-thumbnail {
        border-color: #dc3545 !important;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>

@push('scripts')
<script>
    // 1. Fungsi Ganti Gambar Detail Utama
    function changeMainImage(imageSrc, element) {
        const mainImg = document.getElementById('mainProductImage');
        if (mainImg) {
            mainImg.src = imageSrc;
        }
        document.querySelectorAll('.thumbnail-box').forEach(box => {
            box.classList.remove('active-thumbnail');
        });
        if (element) {
            element.classList.add('active-thumbnail');
        }
    }

    // 2. Fungsi Tombol Plus Minus Kuantitas (Maksimal sesuai stok database)
    function changeQuantity(delta) {
        const qtyInput = document.getElementById('display-qty');
        if (!qtyInput) return;
        
        const maxStok = parseInt(qtyInput.getAttribute('max')) || 0;
        let currentQty = parseInt(qtyInput.value) || 1;
        let newQty = currentQty + delta;
        
        if (newQty >= 1 && newQty <= maxStok) {
            qtyInput.value = newQty;
        }
    }

    // 3. Aksi Submit AJAX Kirim Data JSON Bersih
    document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const addToCartBtn = document.getElementById('addToCartBtn');
        const productId = this.querySelector('input[name="product_id"]').value;
        const qtyValue = parseInt(document.getElementById('display-qty').value) || 1;
        
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';

        if (!csrfToken) {
            alert('Token keamanan kadaluwarsa. Silakan refresh halaman (F5) terlebih dahulu!');
            return;
        }

        // Kunci tombol saat memproses kiriman
        addToCartBtn.disabled = true;
        addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';

        fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: JSON.stringify({
                product_id: productId,
                kuantitas: qtyValue  // Dikirim dengan nama variabel kuantitas murni
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                
                // Beri tahu navbar layouts/app untuk update angka badge live
                window.dispatchEvent(new CustomEvent('cartUpdated', { 
                    detail: { count: data.cart_count } 
                }));
                
                // Lempar ke halaman keranjang belanja
                window.location.href = "{{ route('cart.index') }}";
            } else {
                alert(data.message || 'Gagal menambahkan produk ke keranjang.');
            }
        })
        .catch(error => {
            console.error('Error dari Laravel:', error);
            alert('Terjadi kesalahan sistem server saat menyimpan data. Pastikan status login kamu masih aktif ya!');
        })
        .finally(() => {
            // Lepas kunci tombol setelah selesai proses
            addToCartBtn.disabled = false;
            addToCartBtn.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Tambah ke Keranjang';
        });
    });
</script>
@endpush
@endsection