@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-5" style="max-width: 960px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <h2 class="fw-bold text-dark mb-4 text-uppercase tracking-wider fs-3">KERANJANG BELANJA</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div id="empty-cart-message" class="text-center py-5 border rounded-4 bg-light shadow-sm {{ $carts->isEmpty() ? '' : 'd-none' }}">
        <i class="fas fa-shopping-cart text-muted mb-3" style="font-size: 3rem;"></i>
        <p class="text-secondary fw-medium mb-3">Keranjang belanja kamu saat ini masih kosong.</p>
        <a href="{{ route('shop.index') }}" class="btn btn-danger rounded-pill px-4 fw-bold">Mulai Belanja →</a>
    </div>

    @if (!$carts->isEmpty())
        <form action="{{ route('cart.checkout') }}" method="POST" id="cart-form">
            @csrf

            <div class="table-responsive mb-4">
                <table class="table align-middle border-0 m-0" id="cart-table">

                    <thead>
                        <tr class="text-white align-middle" style="background-color: #b91c1c; font-size: 0.95rem;">
                            <th class="px-4 py-3 fw-semibold border-0 rounded-start" style="width: 35%;">Produk</th>
                            <th class="text-center px-4 py-3 fw-semibold border-0" style="width: 15%;">Harga</th>
                            <th class="text-center px-4 py-3 fw-semibold border-0" style="width: 15%;">Jumlah</th>
                            <th class="text-center px-4 py-3 fw-semibold border-0" style="width: 20%;">Subtotal</th>
                            <th class="text-center px-4 py-3 fw-semibold border-0" style="width: 10%;">Pilih</th>
                            <th class="text-center px-4 py-3 border-0 rounded-end" style="width: 5%;">Aksi</th>
                        </tr>
                    </thead>

                    // Isi Daftar Produk
                    <tbody class="border-0">
                        @foreach ($carts as $cart)
                        <tr class="border-bottom border-2 border-light row-cart-item" id="product-row-{{ $cart->id }}">

                            // Komponen Gambar + Detail Produk
                            <td class="py-4 px-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="border border-secondary-subtle rounded-3 p-1 bg-white flex-shrink-0 d-flex align-items-center justify-content-center" 
                                         style="width: 90px; height: 90px; overflow: hidden;">
                                        <img src="{{ $cart->product->gambar ? asset('products/' . $cart->product->gambar) : asset('images/placeholder.png') }}" 
                                             alt="{{ $cart->product->name }}" 
                                             class="w-100 h-100 object-fit-cover rounded-2">
                                    </div>
                                    <div>
                                        <p class="fw-bold text-dark mb-1 fs-6" style="letter-spacing: 0.3px;">{{ $cart->product->name }}</p>
                                        <p class="text-muted mb-0 font-medium" style="font-size: 0.8rem;">{{ $cart->product->kategori ?? 'Detail Produk' }}</p>
                                    </div>
                                </div>
                            </td>

                            // Harga Satuan
                            <td class="py-4 px-3 text-center fw-semibold text-secondary-dark fs-6 whitespace-nowrap">
                                Rp. {{ number_format($cart->product->harga, 0, ',', '.') }}
                            </td>

                            // Tombol Plus Minus Kotak Abu Sesuai Gambar
                            <td class="py-4 px-3">
                                <div class="d-flex justify-content-center">
                                    <div class="input-group input-group-sm border border-secondary rounded overflow-hidden bg-light" style="width: 90px;">
                                        // Tombol Minus Terbuka Aksesnya Tanpa Atribut Pembatas Min Kaku
                                        <button class="btn btn-link text-decoration-none text-dark fw-bold px-2 py-0 bg-secondary-subtle border-end border-secondary" 
                                                type="button" onclick="updateQty({{ $cart->id }}, -1)">-</button>
                                        
                                        <input type="number" id="qty-{{ $cart->id }}" value="{{ $cart->kuantitas }}" readonly 
                                               class="form-control text-center bg-white border-0 p-0 fw-bold text-dark" style="font-size: 0.85rem; height: 28px;">
                                        
                                        <button class="btn btn-link text-decoration-none text-dark fw-bold px-2 py-0 bg-secondary-subtle border-start border-secondary" 
                                                type="button" onclick="updateQty({{ $cart->id }}, 1)">+</button>
                                    </div>
                                </div>
                            </td>

                            // Hitungan Subtotal Dinamis Riil
                            <td class="py-4 px-3 text-center fw-bold text-dark fs-6 whitespace-nowrap" id="subtotal-{{ $cart->id }}">
                                Rp. {{ number_format($cart->product->harga * $cart->kuantitas, 0, ',', '.') }}
                            </td>

                            // Kotak Checkbox Centang Tebal
                            <td class="py-4 px-3 text-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <input type="checkbox" name="selected[]" value="{{ $cart->id }}" 
                                           data-price="{{ $cart->product->harga }}" 
                                           data-qty="{{ $cart->kuantitas }}" 
                                           class="cart-checkbox form-check-input border-2 border-dark cursor-pointer m-0 rounded-1" 
                                           style="width: 24px; height: 24px; accent-color: #b91c1c;" checked>
                                </div>
                            </td>

                            // AKSI BARU: Tombol Hapus Sampah Langsung Dari Baris Tabel
                            <td class="py-4 px-3 text-center">
                                <button type="button" class="btn btn-link text-danger p-0" onclick="confirmDeleteRow({{ $cart->id }})">
                                    <i class="fas fa-trash-alt fs-5"></i>
                                </button>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            <hr class="text-secondary-subtle border-1 my-4">

            // Ringkasan Grand Total & Tombol Navigasi
            <div id="cart-summary-area" class="d-flex flex-column align-items-end gap-3 mt-3 w-100 pe-2">
                <div class="d-flex align-items-center" style="gap: 60px;">
                    <span class="fw-bold text-dark fs-5">Total:</span>
                    <span class="fw-bold text-dark fs-5" id="grand-total">Rp. 0</span>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('shop.index') }}" 
                       class="btn btn-white border border-dark text-dark fw-bold text-uppercase fs-7 px-4 py-2 rounded-2 tracking-wide shadow-sm hover:bg-dark hover:text-white transition-all text-decoration-none">
                        Lanjut Belanja
                    </a>
                    
                    <button type="submit" 
                            class="btn btn-white border border-danger text-danger fw-bold text-uppercase fs-7 px-4 py-2 rounded-2 tracking-wide shadow-sm hover:bg-danger hover:text-white transition-all" 
                            id="checkout-btn">
                        Checkout (<span id="checkout-count">0</span>)
                    </button>
                </div>
            </div>

        </form>
    @endif
</div>

<script>
    const prices = {};

    // 1. Ambil data peta checkbox acuan pas pertama kali halaman dibuka
    document.querySelectorAll('.cart-checkbox').forEach(cb => {
        prices[cb.value] = {
            price: parseInt(cb.dataset.price) || 0,
            qty:   parseInt(cb.dataset.qty) || 0
        };
        cb.addEventListener('change', updateTotal);
    });

    // 2. Fungsi hitung otomatis grand total & kuantitas
    function updateTotal() {
        let total = 0, count = 0;
        
        document.querySelectorAll('.cart-checkbox:checked').forEach(cb => {
            const id = cb.value;
            if (prices[id]) {
                total += prices[id].price * prices[id].qty;
                count++;
            }
        });

        const grandTotalElement = document.getElementById('grand-total');
        const checkoutCountElement = document.getElementById('checkout-count');
        const checkoutBtn = document.getElementById('checkout-btn');

        if (grandTotalElement) grandTotalElement.textContent = 'Rp. ' + total.toLocaleString('id-ID');
        if (checkoutCountElement) checkoutCountElement.textContent = count;

        if (checkoutBtn) {
            checkoutBtn.disabled = (count === 0);
        }
    }

    // 3. Fungsi plus minus kuantitas sinkron database 
    function updateQty(cartId, delta) {
        const input = document.getElementById('qty-' + cartId);
        if (!input) return;

        const currentQty = parseInt(input.value) || 1;
        const newQty = currentQty + delta;

        // JIKA KUANTITAS SUDAH 1 DAN PEMBELI MENYENTUH TOMBOL MINUS (-), ARTINYA INGIN MENGHAPUS PRODUK
        if (newQty < 1) {
            confirmDeleteRow(cartId);
            return;
        }

        // Jalankan perubahan lokal layar jika kuantitas di atas 1
        input.value = newQty;

        if (prices[cartId]) {
            prices[cartId].qty = newQty;
            const subtotal = prices[cartId].price * newQty;
            document.getElementById('subtotal-' + cartId).textContent = 'Rp. ' + subtotal.toLocaleString('id-ID');
            
            const cb = document.querySelector(`input[value="${cartId}"]`);
            if (cb) {
                cb.dataset.qty = newQty;
            }
        }

        fetch(`/keranjang/${cartId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-HTTP-Method-Override': 'PATCH',
            },
            body: JSON.stringify({ kuantitas: newQty })
        }).catch(err => console.error('Gagal sinkronisasi data kuantitas:', err));

        updateTotal();
    }

    // 4. Pop-Up Konfirmasi Penghapusan Item Keranjang
    function confirmDeleteRow(cartId) {
        const confirmAction = confirm("Apakah kamu yakin ingin menghapus produk ini dari keranjang belanja RUNORA?");
        
        if (confirmAction) {
            // Tembakkan request AJAX DELETE ke server backend Laravel
            fetch(`/keranjang/${cartId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'DELETE',
                }
            })
            .then(response => {
                // Hapus baris tabel di mata browser secara langsung/real-time
                const productRow = document.getElementById('product-row-' + cartId);
                if (productRow) productRow.remove();

                // Buang variabel item dari memori kalkulator javascript
                delete prices[cartId];

                // Hitung ulang total biaya belanja terbaru
                updateTotal();

                // Cek apakah keranjang beneran sudah habis total
                checkEmptyCartState();
            })
            .catch(err => console.error('Gagal menghapus produk:', err));
        }
    }

    // Ngubah tampilan halaman jika isi keranjang telah kosong pasca dihapus
    function checkEmptyCartState() {
        const remainingRows = document.querySelectorAll('.row-cart-item');
        if (remainingRows.length === 0) {
            const cartTableWrapper = document.querySelector('.table-responsive');
            const cartSummaryArea = document.getElementById('cart-summary-area');
            const horizontalRule = document.querySelector('hr');
            
            if (cartTableWrapper) cartTableWrapper.remove();
            if (cartSummaryArea) cartSummaryArea.remove();
            if (horizontalRule) horizontalRule.remove();

            const emptyMessage = document.getElementById('empty-cart-message');
            if (emptyMessage) emptyMessage.classList.remove('d-none');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateTotal();
    });
</script>

<style>
    .fs-7 { font-size: 0.8rem !important; }
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
    .cursor-pointer { cursor: pointer; }
</style>
@endsection