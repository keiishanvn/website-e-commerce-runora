<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Panel - Runora')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden antialiased">

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    {{-- SIDEBAR VERTIKAL --}}
    <div class="w-20 bg-[#C81010] flex flex-col items-center py-6 h-screen justify-between flex-shrink-0 z-10 shadow-2xl">
        
        {{-- Bagian Atas: Profil & Navigasi Utama --}}
        <div class="flex flex-col items-center w-full gap-8">
            {{-- Avatar / Profil Admin --}}
            <a href="#" class="bg-white w-12 h-12 rounded-full flex items-center justify-center shadow-md hover:scale-105 transition-all duration-200" title="Profil Admin">
                <i class="fas fa-user text-black text-lg"></i>
            </a>
            
            {{-- Daftar Menu Navigasi --}}
            <div class="flex flex-col gap-5 items-center w-full">
                
                {{-- Menu 1: Dashboard --}}
                <div class="relative group flex items-center justify-center w-full">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="w-12 h-12 rounded-xl shadow-lg flex items-center justify-center transition-all duration-200 active:scale-90 {{ request()->routeIs('admin.dashboard') ? 'bg-black text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-th-large text-lg"></i>
                    </a>
                    <span class="absolute left-16 ml-2 px-3 py-1 bg-black text-white text-xs rounded-md invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 whitespace-nowrap shadow-xl z-50">Dashboard</span>
                </div>

                {{-- Menu 2: Data Produk --}}
                <div class="relative group flex items-center justify-center w-full">
                    <a href="{{ route('admin.produk.index') }}" 
                       class="w-12 h-12 rounded-xl shadow-lg flex items-center justify-center transition-all duration-200 active:scale-90 {{ request()->routeIs('admin.produk.*') ? 'bg-black text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-shopping-bag text-lg"></i>
                    </a>
                    <span class="absolute left-16 ml-2 px-3 py-1 bg-black text-white text-xs rounded-md invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 whitespace-nowrap shadow-xl z-50">Data Produk</span>
                </div>

                {{-- Menu 3: Distribusi --}}
                <div class="relative group flex items-center justify-center w-full">
                    <a href="{{ route('admin.distribusi') }}"
                       class="w-12 h-12 bg-white rounded-xl shadow-lg flex items-center justify-center hover:bg-gray-100 transition-all duration-200 active:scale-90 text-gray-700">
                        <i class="fas fa-shipping-fast text-lg"></i>
                    </a>
                    <span class="absolute left-16 ml-2 px-3 py-1 bg-black text-white text-xs rounded-md invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 whitespace-nowrap shadow-xl z-50">Distribusi</span>
                </div>

                {{-- Menu 4: Pengaturan --}}
                <div class="relative group flex items-center justify-center w-full">
                    <a href="{{ route('admin.pengaturan') }}" 
                        class="w-12 h-12 bg-white rounded-xl shadow-lg flex items-center justify-center hover:bg-gray-100 transition-all duration-200 active:scale-90 text-gray-700">
                        <i class="fas fa-cog text-lg"></i>
                    </a>
                    <span class="absolute left-16 ml-2 px-3 py-1 bg-black text-white text-xs rounded-md invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 whitespace-nowrap shadow-xl z-50">Pengaturan</span>
                </div>
            </div>
        </div>

        {{-- Bagian Bawah: Tombol Kelola / Keluar Sistem --}}
        <div class="w-full flex justify-center pb-2">
            <div class="relative group flex items-center justify-center w-full">
                <a href="#" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="w-12 h-12 bg-white rounded-xl shadow-lg flex items-center justify-center hover:bg-red-50 transition-all duration-200 active:scale-90 text-red-600"
                   title="Keluar Aplikasi">
                    <i class="fas fa-sign-out-alt text-lg"></i>
                </a>
                <span class="absolute left-16 ml-2 px-3 py-1 bg-black text-white text-xs rounded-md invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 whitespace-nowrap shadow-xl z-50">Keluar</span>
            </div>
        </div>
    </div>

    {{-- AREA KONTEN UTAMA (Kanan Sidebar) --}}
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-hidden">
        <main class="flex-grow p-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>