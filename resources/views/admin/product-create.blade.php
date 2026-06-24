@extends('layouts.admin')

@section('title', 'Tambah Produk Baru - Runora')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.produk.index') }}" class="text-sm font-bold text-[#C81010] hover:underline mb-2 inline-block">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Data Produk
    </a>
    <h1 class="text-3xl font-extrabold text-black tracking-tight uppercase">Tambah Produk Baru</h1>
    <p class="text-gray-500">Lengkapi detail informasi produk Anda di bawah ini.</p>
</div>

@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
        <ul class="list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf 
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm">
                <h2 class="text-lg font-bold mb-6 border-b pb-4">INFORMASI UTAMA</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">NAMA PRODUK</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Masukkan nama produk..." 
                                class="w-full border border-gray-200 p-3.5 rounded-xl outline-none focus:ring-2 focus:ring-[#C81010]/20 focus:border-[#C81010] transition-all">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">KATEGORI</label>
                            <select name="kategori" class="w-full border border-gray-200 p-3.5 rounded-xl outline-none focus:ring-2 focus:ring-[#C81010]/20">
                                <option value="Trail Run" {{ old('kategori') == 'Trail Run' ? 'selected' : '' }}>Trail Run</option>
                                <option value="Running Shoes" {{ old('kategori') == 'Running Shoes' ? 'selected' : '' }}>Running Shoes</option>
                                <option value="Apparel" {{ old('kategori') == 'Apparel' ? 'selected' : '' }}>Apparel</option>
                                <option value="Aksesoris" {{ old('kategori') == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">HARGA SATUAN (RP)</label>
                            <input type="number" name="harga" value="{{ old('harga') }}" required placeholder="0" 
                                   class="w-full border border-gray-200 p-3.5 rounded-xl outline-none focus:ring-2 focus:ring-[#C81010]/20">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">DISKON (%)</label>
                        <input type="number" name="diskon" value="{{ old('diskon', 0) }}" min="0" max="100" class="w-full border border-gray-200 p-3.5 rounded-xl outline-none focus:ring-2 focus:ring-[#C81010]/20">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">DESKRIPSI PRODUK</label>
                        <textarea name="deskripsi" rows="5" placeholder="Jelaskan detail produk..." class="w-full border border-gray-200 p-3.5 rounded-xl outline-none focus:ring-2 focus:ring-[#C81010]/20 resize-none">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm">
                <h2 class="text-lg font-bold mb-4">MANAJEMEN STOK</h2>
                <label class="block text-sm font-bold text-gray-700 mb-2">JUMLAH STOK AWAL</label>
                <input type="number" name="stok" value="{{ old('stok') }}" required placeholder="0" class="w-full border border-gray-200 p-3.5 rounded-xl outline-none focus:ring-2 focus:ring-[#C81010]/20">
            </div>

            // MEDIA PRODUK
            <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm space-y-6">
                <h2 class="text-lg font-bold border-b pb-2">MEDIA PRODUK</h2>
                
<div>
    <label class="block text-xs font-bold text-gray-500 mb-2 uppercase">Gambar Utama (Hero Image)</label>
    <div class="border-2 border-dashed border-gray-200 rounded-2xl p-4 text-center hover:border-[#C81010] transition-all group">
        <i class="fas fa-image text-2xl text-gray-300 mb-2 group-hover:text-[#C81010] block"></i>
        
        <input type="file" name="gambar" id="heroInput" required accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-[#C81010] hover:file:bg-red-100 cursor-pointer mb-2">
        
        <div id="heroPreview" class="mt-3 hidden justify-center">
            <img src="" class="h-24 w-24 object-cover rounded-xl shadow-sm border">
        </div>
    </div>
</div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-2 uppercase">Gambar Detail Tambahan (Bisa Banyak)</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-2xl p-4 text-center hover:border-[#C81010] transition-all group">
                        <i class="fas fa-images text-2xl text-gray-300 mb-2 group-hover:text-[#C81010]"></i>
                        <input type="file" name="images[]" id="detailInput" multiple accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                        <p class="text-[10px] text-gray-400 mt-2">Pilih beberapa foto detail sekaligus</p>
                        
                        <div id="detailPreviewContainer" class="grid grid-cols-3 gap-2 mt-3 hidden">
                            </div>
                    </div>
                </div>
                <p class="text-[10px] text-gray-400 text-center">Format: JPG, PNG (Max 2MB per file)</p>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="w-1/2 bg-[#C81010] text-white py-4 rounded-2xl font-extrabold shadow-lg shadow-red-200 hover:bg-red-700 transition-all active:scale-95 flex items-center justify-center gap-3">
                    <i class="fas fa-save"></i> Simpan Produk
                </button>
                <button type="button" onclick="openModal()" class="w-1/2 text-center bg-gray-200 text-gray-700 py-4 rounded-2xl font-bold hover:bg-gray-300 transition-all active:scale-95">
                    Batal
                </button>
            </div>
        </div>
    </div>
</form>

<div id="confirmModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-80 text-center shadow-xl">
        <h2 class="text-lg font-bold mb-2">Yakin mau batal?</h2>
        <p class="text-gray-500 text-sm mb-6">Semua perubahan yang kamu isi akan hilang.</p>
        <div class="flex gap-3">
            <button onclick="closeModal()" class="w-1/2 bg-gray-200 py-2 rounded-xl font-bold hover:bg-gray-300">Tidak</button>
            <a href="{{ route('admin.produk.index') }}" class="w-1/2 bg-red-600 text-white py-2 rounded-xl font-bold hover:bg-red-700 text-center">Ya, Batal</a>
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

// LIVE PREVIEW GAMBAR UTAMA
document.getElementById('heroInput').addEventListener('change', function(e) {
    const previewDiv = document.getElementById('heroPreview');
    const img = previewDiv.querySelector('img');
    const file = e.target.files[0];
    if (file) {
        img.src = URL.createObjectURL(file);
        previewDiv.classList.remove('hidden');
        previewDiv.classList.add('flex');
    }
});

// LIVE PREVIEW MULTIPLE GAMBAR DETAIL
document.getElementById('detailInput').addEventListener('change', function(e) {
    const container = document.getElementById('detailPreviewContainer');
    container.innerHTML = ''; // Reset preview lama
    const files = e.target.files;
    
    if (files.length > 0) {
        container.classList.remove('hidden');
        Array.from(files).forEach(file => {
            const card = document.createElement('div');
            card.className = 'relative rounded-lg overflow-hidden border bg-gray-50 aspect-square';
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'w-full h-full object-cover';
            card.appendChild(img);
            container.appendChild(card);
        });
    } else {
        container.classList.add('hidden');
    }
});
</script>
@endsection