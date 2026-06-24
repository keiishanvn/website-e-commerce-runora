@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="px-6 py-6">

    // Header 
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">PENGATURAN SISTEM</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola akun admin dan konfigurasi perangkat</p>
    </div>

    // NOTIFIKASI SUKSES / ERROR 
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-sm">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl text-sm flex flex-col gap-1 shadow-sm">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-2 font-semibold">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-7 gap-4">

        // Form Card
        <div class="lg:col-span-4 bg-white rounded-2xl p-6 shadow-sm">

            // Profile
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-full bg-gray-900 flex items-center justify-center text-white text-2xl">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    // AMBIL DARI DATA USER YANG SEDANG LOGIN
                    <p class="font-bold text-gray-900 text-base">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-gray-400">Administrator Sistem</p>
                </div>
            </div>

            // Form (FIX: Action mengarah ke route update)
            <form action="{{ route('admin.pengaturan.update') }}" method="POST">
                @csrf
                @method('PUT') 

                // Email 
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Email</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', Auth::user()->email) }}"
                           placeholder="Masukkan email"
                           class="w-full bg-gray-100 text-gray-800 text-sm px-4 py-3 rounded-xl border-0 outline-none focus:ring-2 focus:ring-red-200">
                </div>

                // Nama Lengkap 
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Nama Lengkap</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', Auth::user()->name) }}"
                           placeholder="Masukkan nama lengkap"
                           class="w-full bg-gray-100 text-gray-800 text-sm px-4 py-3 rounded-xl border-0 outline-none focus:ring-2 focus:ring-red-200">
                </div>

                // Password
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password"
                           name="password"
                           placeholder="Masukkan password baru"
                           class="w-full bg-gray-100 text-gray-800 text-sm px-4 py-3 rounded-xl border-0 outline-none focus:ring-2 focus:ring-red-200">
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="border-2 border-red-700 text-red-700 font-semibold text-sm px-5 py-2 rounded-xl hover:bg-red-700 hover:text-white transition-colors">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.dashboard') }}"
                       class="bg-gray-200 text-gray-700 font-semibold text-sm px-5 py-2 rounded-xl hover:bg-gray-300 transition-colors text-center no-underline">
                        Batal
                    </a>
                </div>

            </form>
        </div>

        // Info Sistem Card
        <div class="lg:col-span-3 bg-white rounded-2xl p-6 shadow-sm h-fit">
            <h5 class="font-bold text-gray-900 text-lg mb-6">Informasi Sistem</h5>

            <div class="flex flex-col gap-5">
                <div class="flex items-center justify-between">
                    <span class="text-gray-400 text-sm">Versi</span>
                    <span class="font-bold text-gray-900 text-sm">v1.0.0</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-400 text-sm">Database</span>
                    <span class="font-bold text-green-500 text-sm">Connected</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-400 text-sm">Server</span>
                    <span class="font-bold text-green-500 text-sm">Connected</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-400 text-sm">Status Server</span>
                    <span class="bg-green-100 text-green-600 text-xs font-semibold px-3 py-1 rounded-full">
                        Online
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection