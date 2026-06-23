<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RUNORA | @yield('title', 'Running Gear & Apparel')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    @stack('styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand logo fw-bold fs-3" href="{{ url('/') }}">
                <span class="text-danger">RUN</span><span>ORA</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-4">
                    <li class="nav-item px-1">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item px-1">
                        <a class="nav-link {{ request()->routeIs('shop.index') || request()->routeIs('shop') ? 'active' : '' }}" href="{{ url('/shop') }}">Produk</a>
                    </li>
                    
                    @if(!Auth::check() || Auth::user()->role === 'pembeli')
                    <li class="nav-item position-relative px-2">
                        <a class="nav-link" href="{{ url('/cart') }}">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                            <span class="cart-count badge bg-danger rounded-pill" id="cartCount" style="position: absolute; top: -2px; right: -12px;">
                                {{ Auth::check() ? \App\Models\Cart::where('user_id', Auth::id())->count() : 0 }}
                            </span>
                        </a>
                    </li>
                    @endif
                    
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle fa-lg"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-th-large text-danger merchants me-2"></i> Panel Admin</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif

                                {{-- FIX UTAMA: Link Profil sekarang mengarah resmi ke halaman Pengaturan Akun --}}
                                <li><a class="dropdown-item" href="{{ route('user.settings') }}"><i class="fas fa-user me-2"></i> Profil</a></li>
                                
                                @if(Auth::user()->role === 'pembeli')
                                    {{-- FIX UTAMA: Link Pesanan sekarang mengarah resmi ke halaman Riwayat Pesanan --}}
                                    <li><a class="dropdown-item" href="{{ route('riwayat.pesanan') }}"><i class="fas fa-shopping-bag me-2"></i> Pesanan</a></li>
                                @endif
                                
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item d-flex gap-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-danger btn-sm px-3">Masuk</a>
                            <a href="{{ route('register') }}" class="btn btn-danger btn-sm px-3">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="logo mb-3"><span class="text-danger">RUN</span><span>ORA</span></h5>
                    <p class="text-muted small">Running gear & apparel for every runner. Stay stylish, keep running.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>Menu</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/') }}" class="text-muted text-decoration-none small">Beranda</a></li>
                        <li><a href="{{ url('/shop') }}" class="text-muted text-decoration-none small">Shop</a></li>
                        <li><a href="{{ url('/cart') }}" class="text-muted text-decoration-none small">Keranjang</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>Kategori</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/shop?kategori=Running+Shoes') }}" class="text-muted text-decoration-none small">Running Shoes</a></li>
                        <li><a href="{{ url('/shop?kategori=Trail+Run') }}" class="text-muted text-decoration-none small">Trail Run</a></li>
                        <li><a href="{{ url('/shop?kategori=Apparel') }}" class="text-muted text-decoration-none small">Apparel</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>Follow Us</h6>
                    <div class="social-icons">
                        <a href="#" class="text-muted me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-muted me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-muted me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-muted"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <hr class="mt-3 bg-secondary">
            <div class="text-center">
                <p class="mb-0 text-muted small">© 2026 RUNORA — Run with spirit.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    
    <script>
        function updateCartCount() {
            fetch('/api/cart/count')
                .then(res => res.json())
                .then(data => {
                    const cartCount = document.getElementById('cartCount');
                    if (cartCount && data.count !== undefined) cartCount.innerText = data.count;
                })
                .catch(err => console.log('Sinyal awal keranjang terwujud.'));
        }
        
        @auth
            @if(Auth::user()->role === 'pembeli')
                document.addEventListener('DOMContentLoaded', updateCartCount);
            @endif
        @endauth

        window.addEventListener('cartUpdated', function(e) {
            const cartBadge = document.getElementById('cartCount');
            if (cartBadge && e.detail && e.detail.count !== undefined) {
                cartBadge.innerText = e.detail.count;
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>