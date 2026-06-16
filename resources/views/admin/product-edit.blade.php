@extends('layouts.admin')

@section('title', 'Edit Produk - Runora')

@section('content')

{{-- ── 1. HEADER HALAMAN ── --}}
<div class="mb-8">
    <a href="{{ route('admin.produk.index') }}" class="text-sm font-bold text-[#C81010] hover:underline mb-2 inline-block">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Data Produk
    </a>
    {{-- FIX: Menggunakan $product->name --}}
    <h1 class="text-3xl font-extrabold text-black tracking-tight uppercase">Edit Produk: {{ $product->name }}</h1>
    <p class="text-gray-500">Edit detail informasi produk Anda di bawah ini.</p>
</div>

{{-- ── 2. ALERT EROR VALIDASI ── --}}
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
        <ul class="list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- ── 3. FORM EDIT PRODUK ── --}}
<form action="{{ route('admin.produk.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf 
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm">
                <h2 class="text-lg font-bold mb-6 border-b pb-4">INFORMASI UTAMA</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">NAMA PRODUK</label>
                        {{-- FIX: name="nama" agar ditangkap aman oleh controller --}}
                        <input type="text" name="nama" required 
                               value="{{ old('nama', $product->name) }}"
                               class="w-full border border-gray-200 p-3.5 rounded-xl outline-none focus:ring-2 focus:ring-[#C81010]/20 focus:border-[#C81010] transition-all">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">KATEGORI</label>
                            <select name="kategori" class="w-full border border-gray-200 p-3.5 rounded-xl outline-none focus:ring-2 focus:ring-[#C81010]/20">
                                <option value="Trail Run" {{ old('kategori', $product->kategori) == 'Trail Run' ? 'selected' : '' }}>Trail Run</option>
                                <option value="Running Shoes" {{ old('kategori', $product->kategori) == 'Running Shoes' ? 'selected' : '' }}>Running Shoes</option>
                                <option value="Apparel" {{ old('kategori', $product->kategori) == 'Apparel' ? 'selected' : '' }}>Apparel</option>
                                <option value="Aksesoris" {{ old('kategori', $product->kategori) == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">HARGA SATUAN (RP)</label>
                            <input type="number" name="harga" 
                                   value="{{ old('harga', $product->harga) }}" required
                                   class="w-full border border-gray-200 p-3.5 rounded-xl outline-none focus:ring-2 focus:ring-[#C81010]/20">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">DISKON (%)</label>
                        <input type="number" name="diskon" 
                               value="{{ old('diskon', $product->diskon) }}" min="0" max="100"
                               class="w-full border border-gray-200 p-3.5 rounded-xl outline-none focus:ring-2 focus:ring-[#C81010]/20">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">DESKRIPSI PRODUK</label>
                        <textarea name="deskripsi" rows="5" class="w-full border border-gray-200 p-3.5 rounded-xl outline-none focus:ring-2 focus:ring-[#C81010]/20 resize-none">{{ old('deskripsi', $product->deskripsi) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm">
                <h2 class="text-lg font-bold mb-4">MANAJEMEN STOK</h2>
                <label class="block text-sm font-bold text-gray-700 mb-2">JUMLAH STOK</label>
                <input type="number" name="stok" 
                       value="{{ old('stok', $product->stok) }}" required
                       class="w-full border border-gray-200 p-3.5 rounded-xl outline-none focus:ring-2 focus:ring-[#C81010]/20">
            </div>

            <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm space-y-6">
                <h2 class="text-lg font-bold border-b pb-2">MEDIA PRODUK</h2>
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-2 uppercase">Gambar Utama (Hero Image)</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-2xl p-4 text-center hover:border-[#C81010] transition-all group">
                        <div id="heroPreview" class="flex justify-center mb-3">
                            @if($product->gambar)
                                <img src="{{ asset('storage/products/' . $product->gambar) }}" class="w-24 h-24 object-cover rounded-xl shadow-sm border">
                            @else
                                <div class="w-24 h-24 bg-gray-50 border rounded-xl flex items-center justify-center text-gray-400"><i class="fas fa-image"></i></div>
                            @endif
                        </div>
                        <input type="file" name="gambar" id="heroInput" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-[#C81010] hover:file:bg-red-100 cursor-pointer">
                        <p class="text-[10px] text-gray-400 mt-2">Biarkan kosong jika tidak ingin mengubah gambar utama.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-2 uppercase">Tambah Gambar Detail Baru</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-2xl p-4 text-center hover:border-[#C81010] transition-all group">
                        <input type="file" name="images[]" id="detailInput" multiple accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                        
                        <div id="detailPreviewContainer" class="grid grid-cols-3 gap-2 mt-3 hidden"></div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="w-1/2 bg-[#C81010] text-white py-4 rounded-2xl font-extrabold shadow-lg shadow-red-200 hover:bg-red-700 transition-all active:scale-95 flex items-center justify-center gap-3">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                 <button type="button" onclick="openModal()" class="w-1/2 text-center bg-gray-200 text-gray-700 py-4 rounded-2xl font-bold hover:bg-gray-300 transition-all active:scale-95">
                    Batal
                </button>
            </div>
        </div>
    </div>
</form>

{{-- ── 4. MODAL KONFIRMASI BATAL ── --}}
<div id="confirmModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-80 text-center shadow-xl">
        <h2 class="text-lg font-bold mb-2">Yakin mau batal?</h2>
        <p class="text-gray-500 text-sm mb-6">Semua perubahan yang kamu isi akan hilang.</p>
        <div class="flex gap-3">
            <button onclick="closeModal()" class="w-1/2 bg-gray-200 py-2 rounded-xl font-bold hover:bg-gray-300">Tidak</button>
            <a href="{{ route('admin.produk.index') }}" class="w-1/2 bg-red-600 text-white py-2 rounded-xl font-bold hover:bg-red-700 text-center flex items-center justify-center">Ya, Batal</a>
        </div>
    </div>
</div>

<script>
function openModal(){
    document.getElementById('confirmModal').classList.remove('hidden');
    document.getElementById('confirmModal').classList.add('flex');
}
function closeModal(){
    document.getElementById('confirmModal').classList.add('hidden');
    document.getElementById('confirmModal').classList.remove('flex');
}

// Live Preview Gambar Utama Baru
document.getElementById('heroInput').addEventListener('change', function(e) {
    const container = document.getElementById('heroPreview');
    const file = e.target.files[0];
    if (file) {
        container.innerHTML = `<img src="${URL.createObjectURL(file)}" class="w-24 h-24 object-cover rounded-xl shadow-sm border">`;
    }
});

// Live Preview Gambar Detail Baru
document.getElementById('detailInput').addEventListener('change', function(e) {
    const container = document.getElementById('detailPreviewContainer');
    container.innerHTML = '';
    const files = e.target.files;
    if (files.length > 0) {
        container.classList.remove('hidden');
        Array.from(files).forEach(file => {
            const card = document.createElement('div');
            card.className = 'relative rounded-lg overflow-hidden border bg-gray-50 aspect-square';
            card.innerHTML = `<img src="${URL.createObjectURL(file)}" class="w-full h-full object-cover">`;
            container.appendChild(card);
        });
    } else {
        container.classList.add('hidden');
    }
});
</script>
@endsection