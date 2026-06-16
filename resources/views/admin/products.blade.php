@extends('layouts.admin')

@section('title', 'Data Produk - Runora')

@section('content')

{{-- ── 1. POPUP NOTIFIKASI SUKSES ── --}}
@if(session('success'))
<div id="successPopup" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-80 text-center shadow-2xl transition-all">
        <i class="fas fa-check-circle text-green-500 text-4xl mb-3"></i>
        <h2 class="text-lg font-bold text-gray-800">Berhasil!</h2>
        <p class="text-gray-500 text-sm mb-4">
            {{ session('success') }}
        </p>
        <button onclick="closePopup()" class="bg-green-500 text-white px-5 py-2 rounded-lg font-bold hover:bg-green-600 transition shadow-sm active:scale-95">
            OK
        </button>
    </div>
</div>
@endif

{{-- ── 2. HEADER HALAMAN ── --}}
<div class="flex justify-between items-start mb-8">
    <div>
        <h1 class="text-2xl font-bold uppercase tracking-tight text-gray-800">DATA PRODUK</h1>
        <p class="text-gray-600 text-sm">Kelola daftar stok dan harga produk Anda</p>
    </div>

    <a href="{{ route('admin.produk.create') }}" class="bg-red-600 text-white px-5 py-3 rounded-xl font-bold flex items-center gap-2 transition hover:bg-red-700 shadow-md active:scale-95">
        <i class="fas fa-plus"></i> Tambah Produk
    </a>
</div>

{{-- ── 3. FORM FILTER PENCARIAN ── --}}
<form method="GET" action="{{ route('admin.produk.index') }}" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-wrap gap-4 items-center">
    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari produk..." class="px-4 py-2 border border-gray-200 rounded-lg w-64 outline-none focus:border-red-500 text-sm transition">

    <select name="kategori" class="px-4 py-2 border border-gray-200 rounded-lg outline-none text-sm focus:border-red-500 bg-white transition">
        <option value="">Semua Kategori</option>
        <option value="Trail Run" {{ ($kategori ?? '') == 'Trail Run' ? 'selected' : '' }}>Trail Run</option>
        <option value="Running Shoes" {{ ($kategori ?? '') == 'Running Shoes' ? 'selected' : '' }}>Running Shoes</option>
        <option value="Apparel" {{ ($kategori ?? '') == 'Apparel' ? 'selected' : '' }}>Apparel</option>
        <option value="Aksesoris" {{ ($kategori ?? '') == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
    </select>

    <select name="urutkan" class="px-4 py-2 border border-gray-200 rounded-lg outline-none text-sm focus:border-red-500 bg-white transition">
        <option value="">Semua Harga</option>
        <option value="termurah" {{ ($urutkan ?? '') == 'termurah' ? 'selected' : '' }}>Harga: Termurah</option>
        <option value="termahal" {{ ($urutkan ?? '') == 'termahal' ? 'selected' : '' }}>Harga: Termahal</option>
    </select>

    <button type="submit" class="bg-gray-700 text-white px-5 py-2 rounded-lg hover:bg-gray-800 font-medium text-sm transition shadow-sm active:scale-95">Cari</button>
    <a href="{{ route('admin.produk.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-200 text-sm font-medium transition flex items-center">Reset</a>
</form>

{{-- ── 4. TABEL UTAMA DATA PRODUK ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-[800px] w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100 text-xs text-gray-400 uppercase font-semibold">
                <tr>
                    <th class="p-4" width="70">No</th>
                    <th class="p-4">Produk</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Harga</th>
                    <th class="p-4" width="100">Diskon</th>
                    <th class="p-4 text-center" width="100">Stok</th>
                    <th class="p-4 text-center" width="150">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                @forelse($products as $index => $row)
                @php
                    $hargaAsli = (float)($row->harga ?? 0); 
                    $diskonPercent = (float)($row->diskon ?? 0); 
                    $hargaDiskon = $hargaAsli - ($hargaAsli * $diskonPercent / 100);
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="p-4 text-gray-400 font-medium">{{ $index + 1 }}</td>
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            @if(!empty($row->gambar))
                                <img src="{{ asset('products/' . $row->gambar) }}" onclick="openImageModal(this.src)" class="w-10 h-10 rounded-xl object-cover cursor-pointer hover:scale-110 transition shadow-sm border border-gray-100">
                            @else
                                <div class="w-10 h-10 bg-gray-50 border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 shadow-sm"><i class="fas fa-image text-sm"></i></div>
                            @endif
                            
                            {{-- FIX UTAMA: Memastikan memanggil kolom 'name' dari database tanpa backtick terbalik --}}
                            <span class="font-semibold text-gray-700">{{ $row->name ?? 'Produk Tanpa Nama' }}</span>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-md text-xs font-semibold">
                            {{ $row->kategori ?? 'Umum' }}
                        </span>
                    </td>
                    <td class="p-4 font-medium">
                        @if($diskonPercent > 0)
                            <span class="line-through text-gray-400 text-xs block">Rp {{ number_format($hargaAsli, 0, ',', '.') }}</span>
                            <span class="text-red-600 font-bold">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                        @else
                            <span class="font-bold text-gray-800">Rp {{ number_format($hargaAsli, 0, ',', '.') }}</span>
                        @endif
                    </td>
                    <td class="p-4">
                        @if($diskonPercent > 0)
                            <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded text-xs font-bold">{{ $diskonPercent }}%</span>
                        @else
                            <span class="text-gray-300 text-xs">-</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <span class="px-2.5 py-1 rounded-md text-xs font-bold {{ $row->stok < 10 ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-green-50 text-green-700 border border-green-100' }}">
                            {{ $row->stok }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.produk.edit', $row->id) }}" class="bg-blue-500 text-white p-2 rounded-xl hover:bg-blue-600 transition shadow-sm active:scale-90"><i class="fas fa-edit text-xs"></i></a>
                            
                            <form action="{{ route('admin.produk.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini dari katalog?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white p-2 rounded-xl hover:bg-red-600 transition shadow-sm active:scale-90"><i class="fas fa-trash text-xs"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-12 text-center text-gray-400 text-sm font-medium">
                        <i class="fas fa-box-open text-3xl block mb-2 text-gray-300"></i>
                        Belum ada data produk atau produk tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── 5. MODAL ZOOM DETAIL GAMBAR ── --}}
<div id="imageModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50 backdrop-blur-sm transition-all duration-300">
    <span onclick="closeImageModal()" class="absolute top-5 right-8 text-white text-3xl cursor-pointer hover:text-gray-300 transition">&times;</span>
    <img id="modalImg" class="max-w-[80%] max-h-[80%] rounded-2xl shadow-2xl transform scale-95 transition-transform duration-300">
</div>

@endsection

@push('scripts')
<script>
function openImageModal(src){
    const modal = document.getElementById("imageModal");
    const img = document.getElementById("modalImg");
    modal.classList.remove("hidden");
    modal.classList.add("flex");
    setTimeout(() => {
        img.classList.remove("scale-95");
        img.classList.add("scale-100");
    }, 50);
    img.src = src;
}

function closeImageModal(){
    const modal = document.getElementById("imageModal");
    const img = document.getElementById("modalImg");
    img.classList.remove("scale-100");
    img.classList.add("scale-95");
    setTimeout(() => {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }, 200);
}

function closePopup(){
    const popup = document.getElementById('successPopup');
    if(popup) {
        popup.style.display = 'none';
    }
}
</script>
@endpush