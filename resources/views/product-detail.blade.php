@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <a href="{{ route('home') }}" class="btn btn-outline-secondary mb-4 rounded-pill px-4">
        <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
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
                // Ambil data gambar detail pendamping langsung berdasarkan ID produk
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
                    
                    {{-- Loop Semua Gambar Detail dari Tabel product_images --}}
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
        
        {{-- ── KANAN: DETAIL INFORMASI & TOMBOL BELI ── --}}
        <div class="col-md-6">
            <h1 class="fw-bold mb-2 text-dark">{{ $product->name }}</h1>
            <span class="badge bg-danger rounded-pill px-3 py-2 mb-3">{{ $product->kategori ?? 'Umum' }}</span>
            
            @php
                $hargaAsli = (float)$product->harga;
                $persenDiskon = (float)$product->diskon;
                $hargaSetelahDiskon = $hargaAsli - ($hargaAsli * $persenDiskon / 100);
            @endphp

            {{-- ── MANAGEMENT TAMPILAN HARGA CORET ── --}}
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
            
            {{-- ── MANAJEMEN STOK ── --}}
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
            
            {{-- ── DESKRIPSI ── --}}
            <div class="mb-4 border-top pt-3">
                <label class="fw-bold text-dark mb-2">Deskripsi Produk:</label>
                <p class="text-secondary lh-base" style="text-align: justify;">{{ $product->deskripsi ?? 'Belum ada deskripsi untuk produk ini.' }}</p>
            </div>
            
            {{-- ── PILIHAN UKURAN ── --}}
            <div class="mb-4 border-top pt-3">
                <label class="fw-semibold mb-2">Pilih Ukuran <span class="text-danger">*</span></label>
                <div class="d-flex gap-2 flex-wrap" id="sizeOptions">
                    @php
                        $sizes = isset($product->sizes) ? (is_array($product->sizes) ? $product->sizes : json_decode($product->sizes, true)) : null;
                    @endphp
                    @if($sizes && count($sizes) > 0)
                        @foreach($sizes as $size)
                            <button type="button" class="btn-size" data-size="{{ $size }}">
                                {{ $size }}
                            </button>
                        @endforeach
                    @else
                        @foreach(['39', '40', '41', '42', '43'] as $dummySize)
                            <button type="button" class="btn-size" data-size="{{ $dummySize }}">
                                {{ $dummySize }}
                            </button>
                        @endforeach
                    @endif
                </div>
                <input type="hidden" id="selectedSize" name="selectedSize" value="">
                <small class="text-danger mt-1 fw-medium" id="sizeWarning" style="display: none;">
                    <i class="fas fa-info-circle me-1"></i> Silakan pilih ukuran sepatu/baju terlebih dahulu!
                </small>
            </div>
            
            {{-- ── MANAGEMENT INPUT QUANTITY & BELI ── --}}
            <div class="mt-4 border-top pt-3">
                <label class="fw-semibold mb-2">Jumlah:</label>
                <div class="d-flex align-items-center gap-3 mb-4">
                    <button class="btn btn-outline-secondary rounded-circle px-3" id="decreaseQty" 
                            type="button" {{ $product->stok <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-minus"></i>
                    </button>
                    
                    <input type="number" id="quantity" value="{{ $product->stok > 0 ? 1 : 0 }}" min="{{ $product->stok > 0 ? 1 : 0 }}" max="{{ $product->stok }}" 
                           style="width: 80px; text-align: center;" class="form-control text-center fw-bold" 
                           {{ $product->stok <= 0 ? 'disabled' : '' }}>
                    
                    <button class="btn btn-outline-secondary rounded-circle px-3" id="increaseQty" 
                            type="button" {{ $product->stok <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                
                <button class="btn btn-danger btn-lg w-100 py-3 rounded-pill fw-bold shadow-sm" id="addToCartBtn" 
                        data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->name }}"
                        data-product-price="{{ $product->diskon > 0 ? $hargaSetelahDiskon : $product->harga }}"
                        {{ $product->stok <= 0 ? 'disabled' : '' }}>
                    <i class="fas fa-shopping-cart me-2"></i>
                    {{ $product->stok > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-size {
        padding: 10px 20px;
        border: 1px solid #ddd;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
    }
    .btn-size:hover {
        background: #f8f9fa;
        border-color: #dc3545;
    }
    .btn-size.active {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
    }
    .btn-size:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
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
    .cart-toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        z-index: 9999;
        animation: slideIn 0.3s ease;
    }
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>

@push('scripts')
<script>
    // 1. JAVASCRIPT FITUR KLIK THUMBNAIL UNTUK UBAH GAMBAR UTAMA
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

    // INTERAKSI ELEMENT HTML
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const quantityInput = document.getElementById('quantity');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const sizeButtons = document.querySelectorAll('.btn-size');
    const selectedSizeInput = document.getElementById('selectedSize');
    const sizeWarning = document.getElementById('sizeWarning');
    
    let selectedSize = null;
    
    // 2. LOGIKA TOMBOL QUANTITY (MINUS)
    if (decreaseBtn && quantityInput) {
        decreaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value) || 0;
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
    }
    
    // 3. LOGIKA TOMBOL QUANTITY (PLUS)
    if (increaseBtn && quantityInput) {
        increaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value) || 0;
            let maxStock = parseInt(quantityInput.getAttribute('max')) || 0;
            if (currentValue < maxStock) {
                quantityInput.value = currentValue + 1;
            }
        });
    }
    
    // 4. LOGIKA PILIH UKURAN SEPATU/APPAREL
    sizeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            sizeButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedSize = this.getAttribute('data-size');
            if (selectedSizeInput) {
                selectedSizeInput.value = selectedSize;
            }
            if (sizeWarning) {
                sizeWarning.style.display = 'none';
            }
        });
    });
    
    // 5. FUNCTION NOTIFIKASI TOAST (DIKUMPULKAN DALAM BUNGKUS SCRIPT YANG SAMA)
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = 'cart-toast';
        
        if (type === 'success') {
            toast.style.backgroundColor = '#28a745'; 
        } else if (type === 'warning') {
            toast.style.backgroundColor = '#ffc107'; 
            toast.style.color = '#000';
        } else {
            toast.style.backgroundColor = '#dc3545'; 
        }
        
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => { toast.remove(); }, 3000);
    }
    
    // 6. VALIDASI MANUAL KETIKA INPUT JUMLAH DIKETIK KIBOR
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            let value = parseInt(this.value);
            const min = parseInt(this.getAttribute('min')) || 1;
            const max = parseInt(this.getAttribute('max')) || 999;
            if (isNaN(value) || value < min) {
                this.value = min;
            } else if (value > max) {
                this.value = max;
                showToast('warning', `Stok hanya tersedia ${max} item`);
            }
        });
    }

    // 7. PROSES AJAX ADD TO CART (ANTI-CRASH & UPDATE NAVBAR REAL-TIME)
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', async function() {
            if (!selectedSize) {
                if (sizeWarning) {
                    sizeWarning.style.display = 'block';
                    setTimeout(() => { sizeWarning.style.display = 'none'; }, 3000);
                }
                return;
            }
            
            const productId = this.getAttribute('data-product-id');
            const productPrice = this.getAttribute('data-product-price');
            const quantity = parseInt(quantityInput.value) || 1;
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            
            if (!tokenMeta) {
                showToast('error', 'Token CSRF tidak ditemukan!');
                return;
            }
            
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            
            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': tokenMeta.getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity,
                        size: selectedSize,
                        price: productPrice
                    })
                });
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw errorData;
                }
                
                const data = await response.json();
                
                if (data.success) {
                    showToast('success', data.message || 'Produk berhasil masuk keranjang!');
                    
                    // Lempar sinyal global membawa angka keranjang terbaru ke navbar app.blade.php
                    window.dispatchEvent(new CustomEvent('cartUpdated', { 
                        detail: { count: data.cart_count } 
                    }));
                } else {
                    showToast('error', data.message || 'Gagal menambahkan ke keranjang');
                }
            } catch (error) {
                console.error('Error detail:', error);
                
                if (error.message === 'Unauthenticated.' || error.error === 'Unauthenticated') {
                    showToast('error', 'Silakan login terlebih dahulu untuk belanja!');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                } else {
                    showToast('error', error.message || 'Terjadi kesalahan. Silakan coba lagi.');
                }
            } finally {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Tambah ke Keranjang';
            }
        });
    }
</script>
@endpush
@endsection