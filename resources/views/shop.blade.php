@extends('layouts.app')

@section('title', 'Shop - Running Gear & Apparel')

@section('content')
<section class="container py-4">
    <h2 class="section-title font-weight-bold mb-4">Semua Produk</h2>
    
    {{-- ── BUTTON FILTER KATEGORI (FIXED: Menyesuaikan parameter request 'kategori') ── --}}
    <div class="d-flex gap-2 mb-4 flex-wrap" id="categoryFilters">
        {{-- Tangkap kategori aktif dari controller lewat variabel $kategoriAktif --}}
        @php $kategoriAktif = request()->get('kategori', 'all'); @endphp
        
        <button class="btn btn-filter {{ $kategoriAktif == 'all' ? 'active' : '' }}" data-cat="all">Semua</button>
        <button class="btn btn-filter {{ $kategoriAktif == 'Running Shoes' ? 'active' : '' }}" data-cat="Running Shoes">Running Shoes</button>
        <button class="btn btn-filter {{ $kategoriAktif == 'Trail Run' ? 'active' : '' }}" data-cat="Trail Run">Trail Run</button>
        <button class="btn btn-filter {{ $kategoriAktif == 'Apparel' ? 'active' : '' }}" data-cat="Apparel">Apparel</button>
        <button class="btn btn-filter {{ $kategoriAktif == 'Aksesoris' ? 'active' : '' }}" data-cat="Aksesoris">Aksesoris</button>
    </div>
    
    {{-- ── GRID DAFTAR PRODUK KATALOG ── --}}
    <div class="row g-4" id="productsGrid">
        @forelse($products as $product)
        <div class="col-md-3">
            {{-- FIX: Mengubah nama route dari 'product.detail' menjadi 'product.show' sesuai web.php --}}
            <div class="product-card border rounded shadow-sm bg-white h-100 p-3 flex-column justify-between d-flex position-relative overflow-hidden" 
                 onclick="window.location.href='{{ route('product.show', $product->id) }}'"
                 style="cursor: pointer; transition: transform 0.2s;"
                 onmouseover="this.style.transform='scale(1.03)'"
                 onmouseout="this.style.transform='scale(1)'">
                
                {{-- AREA FOTO PRODUK UTAMA --}}
                <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded mb-3 overflow-hidden position-relative" style="height: 180px;">
                    {{-- FIX: Menggunakan folder 'products/' sesuai trik move(public_path) --}}
                    @if($product->gambar)
                        <img src="{{ asset('products/' . $product->gambar) }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                    @else
                        <span class="text-center p-2 text-xs">[ {{ $product->name }} ]</span>
                    @endif

                    {{-- Tag Badge Persen Diskon --}}
                    @if($product->diskon > 0)
                        <span class="position-absolute top-0 start-0 badge bg-danger m-2 text-xs">
                            -{{ $product->diskon }}%
                        </span>
                    @endif
                </div>

                {{-- AREA INFORMASI PRODUK --}}
                <div class="product-info mt-auto">
                    {{-- FIX: Memanggil $product->kategori --}}
                    <span class="badge bg-danger mb-2">{{ $product->kategori ?? 'Running Gear' }}</span>
                    <h6 class="text-dark font-weight-bold text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
                    
                    @php
                        $hargaAsli = (float)$product->harga;
                        $persenDiskon = (float)$product->diskon;
                        $hargaSetelahDiskon = $hargaAsli - ($hargaAsli * $persenDiskon / 100);
                    @endphp

                    {{-- Perhitungan Skema Harga Coret --}}
                    <div class="mt-2">
                        @if($persenDiskon > 0)
                            <span class="text-muted text-decoration-line-through text-sm d-block" style="font-size: 0.85rem;">
                                Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                            </span>
                            <span class="text-danger fw-bold fs-5">
                                Rp {{ number_format($hargaSetelahDiskon, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="text-dark fw-bold fs-5">
                                Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        {{-- Jika produk di kategori tersebut kosong --}}
        <div class="col-114 text-center py-5 text-muted">
            <i class="fas fa-box-open fa-3x mb-3 text-secondary"></i>
            <h5>Belum ada produk untuk kategori ini.</h5>
        </div>
        @endforelse
    </div>
</section>
@endsection

@push('scripts')
<script>
    // FIX JAVASCRIPT FILTER KATEGORI: Menembak parameter URL '?kategori=' sesuai standard penamaan database
    document.querySelectorAll('.btn-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            const cat = this.dataset.cat;
            if (cat === 'all') {
                window.location.href = '/shop'; // Kalau pilih Semua, hilangkan filter URL-nya
            } else {
                window.location.href = `/shop?kategori=${encodeURIComponent(cat)}`;
            }
        });
    });
</script>
@endpush