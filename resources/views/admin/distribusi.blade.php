@extends('layouts.admin')

@section('title', 'Distribusi Produk')

@section('content')
<div class="px-6 py-6">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">DISTRIBUSI PRODUK</h2>
            <p class="text-sm text-gray-500 mt-1">Pantau pengiriman dan distribusi produk olahraga secara real-time</p>
        </div>
        <button class="flex items-center gap-2 border-2 border-red-700 text-red-700 font-semibold text-sm px-5 py-2 rounded-full hover:bg-red-700 hover:text-white transition-colors">
            + Tambah Distribusi
        </button>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- Total Distribusi (Dinamis) --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-md">Hari Ini</span>
            </div>
            <p class="text-sm text-gray-400 font-medium">Total Distribusi</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalDistribusi) }}</p>
        </div>

        {{-- Sedang Diproses (Dinamis) --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center text-xl mb-3">
                <i class="far fa-clock"></i>
            </div>
            <p class="text-sm text-gray-400 font-medium">Sedang Diproses</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($sedangDiproses) }}</p>
        </div>

        {{-- Terkirim --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl mb-3">
                <i class="far fa-check-circle"></i>
            </div>
            <p class="text-sm text-gray-400 font-medium">Terkirim</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">0</p>
        </div>

        {{-- Kendala --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-400 flex items-center justify-center text-xl mb-3">
                <i class="fas fa-times-circle"></i>
            </div>
            <p class="text-sm text-gray-400 font-medium">Kendala Pengiriman</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">0</p>
        </div>

    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        {{-- Table Header --}}
        <div class="bg-red-800 px-6 py-4">
            <p class="text-white font-bold text-lg leading-tight">Data Distribusi</p>
            <p class="text-red-200 text-xs mt-0.5">Riwayat pengiriman produk olahraga lari</p>
        </div>

        {{-- Table Main Content --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-100">
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-4">ID / Invoice</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-4">Produk</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-4">Tujuan</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-4">Kurir</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-4">Tanggal</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-4">Status</th>
                        <th class="text-center text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @php
                        // Array untuk kebutuhan mix nama kurir secara acak
                        $daftarKurir = ['Anteraja', 'Sicepat', 'J&T Express', 'JNE Reguler', 'Grab Delivery'];
                    @endphp

                    @forelse($distribusiData as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        {{-- Kolom ID: Mengambil nomor invoice dari database --}}
                        <td class="px-5 py-4 font-bold text-gray-900">{{ $order->invoice_number }}</td>
                        
                        {{-- Kolom Produk: FIX menggunakan relasi $order->items sesuai model kamu --}}
                        <td class="px-5 py-4 max-w-[180px]">
                            @if($order->items && $order->items->isNotEmpty())
                                <p class="font-semibold text-gray-800">{{ $order->items->first()->product->name ?? 'Produk Runora' }}</p>
                                @if($order->items->count() > 1)
                                    <span class="text-[10px] text-gray-400 block">+{{ $order->items->count() - 1 }} produk lainnya</span>
                                @endif
                            @else
                                <p class="text-xs text-gray-400 italic">Tidak ada item</p>
                            @endif
                        </td>
                        
                        {{-- Kolom Tujuan: Mengambil alamat pembeli dari database --}}
                        <td class="px-5 py-4 max-w-[240px]">
                            <p class="text-gray-600 leading-relaxed line-clamp-2" title="{{ $order->alamat }}">{{ $order->alamat }}</p>
                        </td>
                        
                        {{-- Kolom Kurir: Di-mix otomatis menggunakan array generator --}}
                        <td class="px-5 py-4 text-gray-700 font-medium">
                            {{ $daftarKurir[($order->id) % count($daftarKurir)] }}
                        </td>
                        
                        {{-- Kolom Tanggal: Mengambil data created_at dari database --}}
                        <td class="px-5 py-4 text-gray-700">
                            {{ $order->created_at ? $order->created_at->format('d-m-Y') : '-' }}
                        </td>
                        
                        {{-- Kolom Status: Sesuai permintaan dipaksa menjadi 'Diproses' --}}
                        <td class="px-5 py-4">
                            <span class="inline-block bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full">Diproses</span>
                        </td>
                        
                        <td class="px-5 py-4 text-center space-x-1">
                            <button class="text-cyan-500 hover:scale-110 transition-transform px-1" title="Lihat"><i class="far fa-eye"></i></button>
                            <button class="text-yellow-400 hover:scale-110 transition-transform px-1" title="Edit"><i class="far fa-edit"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-400 text-xs">Belum ada data distribusi produk yang terdaftar.</td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection