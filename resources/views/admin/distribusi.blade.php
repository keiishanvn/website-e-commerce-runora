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

        {{-- Total Distribusi --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-md">Hari Ini</span>
            </div>
            <p class="text-sm text-gray-400 font-medium">Total Distribusi</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">48</p>
        </div>

        {{-- Sedang Diproses --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center text-xl mb-3">
                <i class="far fa-clock"></i>
            </div>
            <p class="text-sm text-gray-400 font-medium">Sedang Diproses</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">12</p>
        </div>

        {{-- Terkirim --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl mb-3">
                <i class="far fa-check-circle"></i>
            </div>
            <p class="text-sm text-gray-400 font-medium">Terkirim</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">30</p>
        </div>

        {{-- Kendala --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-400 flex items-center justify-center text-xl mb-3">
                <i class="fas fa-times-circle"></i>
            </div>
            <p class="text-sm text-gray-400 font-medium">Kendala Pengiriman</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">6</p>
        </div>

    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        {{-- Table Header --}}
        <div class="bg-red-800 px-6 py-4">
            <p class="text-white font-bold text-lg leading-tight">Data Distribusi</p>
            <p class="text-red-200 text-xs mt-0.5">Riwayat pengiriman produk olahraga lari</p>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-100">
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-4">ID</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-4">Produk</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-4">Tujuan</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-4">Kurir</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-4">Tanggal</th>
                        <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-4">Status</th>
                        <th class="text-center text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">

                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4 font-bold text-gray-900">#INV-001</td>
                        <td class="px-5 py-4 max-w-[180px]">
                            <p class="font-semibold text-gray-800">RUNORA Trail & Outdoor</p>
                            <p class="text-xs text-gray-400">(Army Green)</p>
                        </td>
                        <td class="px-5 py-4 max-w-[240px]">
                            <p class="text-gray-600 leading-relaxed">Kelurahan Jatimulya, Kecamatan Tambun Selatan, Kabupaten Bekasi, Jawa Barat</p>
                        </td>
                        <td class="px-5 py-4 text-gray-700">Anteraja</td>
                        <td class="px-5 py-4 text-gray-700">01-05-2026</td>
                        <td class="px-5 py-4">
                            <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">Terkirim</span>
                        </td>
                        <td class="px-5 py-4 text-center space-x-1">
                            <button class="text-cyan-500 hover:scale-110 transition-transform px-1" title="Lihat"><i class="far fa-eye"></i></button>
                            <button class="text-yellow-400 hover:scale-110 transition-transform px-1" title="Edit"><i class="far fa-edit"></i></button>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4 font-bold text-gray-900">#INV-002</td>
                        <td class="px-5 py-4 max-w-[180px]">
                            <p class="font-semibold text-gray-800">RUNORA Bolt Performance Cap</p>
                        </td>
                        <td class="px-5 py-4 max-w-[240px]">
                            <p class="text-gray-600 leading-relaxed">Kelurahan Sukasari, Kecamatan Bogor Timur, Kota Bogor, Jawa Barat</p>
                        </td>
                        <td class="px-5 py-4 text-gray-700">Sicepathalu</td>
                        <td class="px-5 py-4 text-gray-700">01-05-2026</td>
                        <td class="px-5 py-4">
                            <span class="inline-block bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full">Gagal</span>
                        </td>
                        <td class="px-5 py-4 text-center space-x-1">
                            <button class="text-cyan-500 hover:scale-110 transition-transform px-1" title="Lihat"><i class="far fa-eye"></i></button>
                            <button class="text-yellow-400 hover:scale-110 transition-transform px-1" title="Edit"><i class="far fa-edit"></i></button>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4 font-bold text-gray-900">#INV-003</td>
                        <td class="px-5 py-4 max-w-[180px]">
                            <p class="font-semibold text-gray-800">Natura Headband</p>
                        </td>
                        <td class="px-5 py-4 max-w-[240px]">
                            <p class="text-gray-600 leading-relaxed">Kelurahan Karet Tengsin, Kecamatan Tanah Abang, Jakarta Pusat</p>
                        </td>
                        <td class="px-5 py-4 text-gray-700">Sicepathalu</td>
                        <td class="px-5 py-4 text-gray-700">01-05-2026</td>
                        <td class="px-5 py-4">
                            <span class="inline-block bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full">Diproses</span>
                        </td>
                        <td class="px-5 py-4 text-center space-x-1">
                            <button class="text-cyan-500 hover:scale-110 transition-transform px-1" title="Lihat"><i class="far fa-eye"></i></button>
                            <button class="text-yellow-400 hover:scale-110 transition-transform px-1" title="Edit"><i class="far fa-edit"></i></button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection