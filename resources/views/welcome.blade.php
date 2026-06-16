@extends('layouts.app')

@section('title', 'Home - Running Gear & Apparel')

@section('content')
    {{-- ── 1. AREA HERO CAROUSEL BANNER ── --}}
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
        </div>
        
        <div class="carousel-inner">
            {{-- Slide 1 --}}
            <div class="carousel-item active" style="background-image: linear-gradient(105deg, rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('images/hero1.jpg') }}'); background-size: cover; background-position: center; height: 500px;">
                <div class="carousel-caption d-flex flex-column justify-content-center h-100">
                    <div class="hero-content text-start">
                        <h1 class="animate__animated animate__fadeInUp display-4 fw-bold text-white">Run with Style</h1>
                        <p class="lead text-white-100">Temukan produk olahraga terbaik untukmu</p>
                        <a href="#featuredProducts" class="btn btn-danger btn-lg px-4 py-2.5 rounded-4">Belanja Sekarang →</a>
                    </div>
                </div>
            </div>

            {{-- Slide 2 --}}
            <div class="carousel-item" style="background-image: linear-gradient(105deg, rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('images/hero2.jpg') }}'); background-size: cover; background-position: center; height: 500px;">
                <div class="carousel-caption d-flex flex-column justify-content-center h-100">
                    <div class="hero-content text-start">
                        <h1 class="display-4 fw-bold text-white">Premium Running Shoes</h1>
                        <p class="lead text-white-100">Ringan, nyaman, dan siap menemani setiap langkahmu</p> 
                        <a href="#featuredProducts" class="btn btn-danger btn-lg px-4 py-2.5 rounded-4">Belanja Sekarang →</a>
                    </div>
                </div>
            </div>

            {{-- Slide 3 --}}
            <div class="carousel-item" style="background-image: linear-gradient(105deg, rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('images/hero3.jpg') }}'); background-size: cover; background-position: center; height: 500px;">
                <div class="carousel-caption d-flex flex-column justify-content-center h-100">
                    <div class="hero-content text-start">
                        <h1 class="display-4 fw-bold text-white">Train Hard, Run Better</h1>
                        <p class="lead text-white-100">Apparel dan aksesoris terbaik untuk performa maksimal</p>
                        <a href="#featuredProducts" class="btn btn-danger btn-lg px-4 py-2.5 rounded-4">Belanja Sekarang →</a>
                    </div>
                </div>
            </div>

            {{-- Slide 4 --}}
            <div class="carousel-item" style="background-image: linear-gradient(105deg, rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('images/hero4.jpg') }}'); background-size: cover; background-position: center; height: 500px;">
                <div class="carousel-caption d-flex flex-column justify-content-center h-100">
                    <div class="hero-content text-start">
                        <h1 class="display-4 fw-bold text-white">Train Hard, Run Better</h1>
                        <p class="lead text-white-100">It's okay, the snail pace, the important thing is my style is perfect</p>
                        <a href="#featuredProducts" class="btn btn-danger btn-lg px-4 py-2.5 rounded-4">Belanja Sekarang →</a>
                    </div>
                </div>
            </div>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    {{-- ── 2. AREA GRID KATEGORI PRODUK ── --}}
    <section class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title font-weight-bold mb-0">Kategori Produk</h2>
            <a href="{{ url('/shop') }}" class="text-danger fw-semibold text-decoration-none link-view-all">
                Lihat Semua <i class="fas fa-chevron-right ms-1 small"></i>
            </a>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="category-card text-center p-4 border rounded shadow-sm bg-white">
                    <div class="category-icon-wrapper mb-3" style="font-size: 2.5rem;"><i class="fas fa-shoe-prints text-danger"></i></div>
                    <h4>Running Shoes</h4>
                    <a href="{{ url('/shop?kategori=Running+Shoes') }}" class="btn btn-outline-danger btn-sm rounded-pill px-4 mt-2">Lihat Semua →</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card text-center p-4 border rounded shadow-sm bg-white">
                    <div class="category-icon-wrapper mb-3" style="font-size: 2.5rem;"><i class="fas fa-mountain text-danger"></i></div>
                    <h4>Trail Run</h4>
                    <a href="{{ url('/shop?kategori=Trail+Run') }}" class="btn btn-outline-danger btn-sm rounded-pill px-4 mt-2">Lihat Semua →</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card text-center p-4 border rounded shadow-sm bg-white">
                    <div class="category-icon-wrapper mb-3" style="font-size: 2.5rem;"><i class="fas fa-tshirt text-danger"></i></div>
                    <h4>Apparel</h4>
                    <a href="{{ url('/shop?kategori=Apparel') }}" class="btn btn-outline-danger btn-sm rounded-pill px-4 mt-2">Lihat Semua →</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ── 3. AREA DAFTAR PRODUK TERLARIS ── --}}
    <section class="container py-5" id="featuredProducts">
        <h2 class="section-title font-weight-bold">Produk Terlaris</h2>
        <div class="row g-4 mt-2">
            @foreach($products as $product)
            <div class="col-md-3">
                <div class="product-card border rounded shadow-sm bg-white h-100 p-3 flex-column justify-between d-flex" 
                     onclick="window.location.href='{{ route('product.show', $product->id) }}'" 
                     style="cursor: pointer; transition: transform 0.2s;"
                     onmouseover="this.style.transform='scale(1.03)'"
                     onmouseout="this.style.transform='scale(1)'">
                    
                    {{-- AREA FOTO PRODUK UTAMA --}}
                    <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded mb-3 overflow-hidden position-relative" style="height: 200px;">
                        @if($product->gambar)
                            <img src="{{ asset('products/' . $product->gambar) }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <span class="text-center p-2 text-xs">[ {{ $product->name }} ]</span>
                        @endif

                        {{-- Tag Badge Persen Diskon Pojok Gambar --}}
                        @if($product->diskon > 0)
                            <span class="position-absolute top-0 start-0 badge bg-danger m-2 text-xs">
                                -{{ $product->diskon }}%
                            </span>
                        @endif
                    </div>

                    {{-- AREA INFORMASI PRODUK --}}
                    <div class="product-info mt-auto">
                        <span class="badge bg-danger mb-2">{{ $product->kategori ?? 'Running Gear' }}</span>
                        <h6 class="text-dark font-weight-bold text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
                        
                        @php
                            $hargaAsli = (float)$product->harga;
                            $persenDiskon = (float)$product->diskon;
                            $hargaSetelahDiskon = $hargaAsli - ($hargaAsli * $persenDiskon / 100);
                        @endphp

                        {{-- Perhitungan Skema Harga Coret Diskon Persen --}}
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
            @endforeach
        </div>
    </section>

    <style>
        .link-view-all {
            transition: color 0.2s ease;
        }
        .link-view-all:hover {
            text-decoration: underline !important;
            color: #bd2130 !important; 
        }
    </style>
@endsection