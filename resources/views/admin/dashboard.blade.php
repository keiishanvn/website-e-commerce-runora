@extends('layouts.admin')

@section('title', 'Dashboard Admin - Runora')

@section('content')
<div class="space-y-6 animate__animated animate__fadeIn">
    <div>
        <h4 class="text-2xl font-bold text-gray-800 tracking-tight">DASHBOARD ADMIN</h4>
        <p class="text-sm text-gray-500">Kelola dan pantau kondisi toko anda</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-wallet text-xl"></i>
            </div>
            <div>
                <span class="text-xs text-gray-400 block font-medium">Total Penjualan</span>
                <h5 class="text-xl font-bold text-gray-800 mt-0.5">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h5>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-archive text-xl"></i>
            </div>
            <div>
                <span class="text-xs text-gray-400 block font-medium">Total Pesanan</span>
                <h5 class="text-xl font-bold text-gray-800 mt-0.5">{{ number_format($totalPesanan) }} <span class="text-sm text-gray-400 font-normal">Pesanan</span></h5>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-shopping-bag text-xl"></i>
            </div>
            <div>
                <span class="text-xs text-gray-400 block font-medium">Total Produk</span>
                <h5 class="text-xl font-bold text-gray-800 mt-0.5">{{ number_format($totalProduk) }} <span class="text-sm text-gray-400 font-normal">Produk</span></h5>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h6 class="font-bold text-gray-800 mb-4 text-base">Top 5 Produk Terlaris</h6>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-xs text-gray-400 uppercase font-semibold">
                        <th class="pb-3" width="60">No</th>
                        <th class="pb-3">Produk</th>
                        <th class="pb-3 text-center">Terjual</th>
                        <th class="pb-3 text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                    @forelse($topProduk as $index => $item)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 text-gray-400 font-medium">{{ $index + 1 }}</td>
                        <td class="py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-50 border border-gray-100 rounded-xl flex items-center justify-center text-gray-400">
                                    <i class="far fa-image"></i>
                                </div>
                                <span class="font-semibold text-gray-700">{{ $item->nama_produk }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 text-center font-semibold text-gray-500">{{ $item->total_terjual }}</td>
                        <td class="py-3.5 text-right font-bold text-red-600">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-400 text-xs py-4">Belum ada data penjualan produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 md:col-span-5 flex flex-col justify-between">
            <h6 class="font-bold text-gray-800 mb-4 text-base">Stok Produk</h6>
            <div class="space-y-3">
                <div class="flex justify-content-between items-center p-3 bg-yellow-50/50 border border-yellow-100/50 rounded-xl">
                    <span class="text-xs font-medium text-yellow-700 flex items-center"><i class="fas fa-exclamation-triangle mr-2"></i> Stok Menipis (&lt;10)</span>
                    <span class="text-xs font-bold text-yellow-700">{{ $stokMenipis }} Produk</span>
                </div>
                <div class="flex justify-content-between items-center p-3 bg-green-50/50 border border-green-100/50 rounded-xl">
                    <span class="text-xs font-medium text-green-700 flex items-center"><i class="fas fa-box-seam mr-2"></i> Stok Tersedia</span>
                    <span class="text-xs font-bold text-green-700">{{ $stokTersedia }} Produk</span>
                </div>
                <div class="flex justify-content-between items-center p-3 bg-red-50/50 border border-red-100/50 rounded-xl">
                    <span class="text-xs font-medium text-red-700 flex items-center"><i class="fas fa-times-circle mr-2"></i> Stok Habis</span>
                    <span class="text-xs font-bold text-red-700">{{ $stokHabis }} Produk</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 md:col-span-7">
            <h6 class="font-bold text-gray-800 mb-4 text-base">Aktivitas Terbaru</h6>
            <div class="space-y-4">
                @forelse($aktivitasTerbaru as $act)
                <div class="flex items-start justify-between border-b border-gray-50 pb-3 last:border-0 last:pb-0">
                    <div class="flex gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 {{ $act->tipe == 'produk_ditambah' ? 'bg-green-50 text-green-600' : 'bg-orange-50 text-orange-600' }}">
                            <i class="fas {{ $act->tipe == 'produk_ditambah' ? 'fa-plus-circle' : 'fa-minus-circle' }} text-sm"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-700">{{ $act->deskripsi }}</span>
                            <small class="text-[10px] text-gray-400">Oleh: {{ $act->user_name }}</small>
                        </div>
                    </div>
                    <small class="text-[10px] text-gray-400 font-medium">{{ $act->waktu }}</small>
                </div>
                @empty
                <div class="text-center text-gray-400 text-xs py-6">Belum ada log aktivitas terbaru.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 md:col-span-8">
            <h6 class="font-bold text-gray-800 mb-4 text-base">Grafik Penjualan</h6>
            <div class="h-64 relative">
                <canvas id="chartPenjualan"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 md:col-span-4">
            <h6 class="font-bold text-gray-800 mb-4 text-base">Status Pesanan</h6>
            <div class="h-64 relative flex flex-col items-center justify-center">
                <div class="w-full h-full max-h-48 max-w-48">
                    <canvas id="chartStatus"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const salesData = @json($grafikPenjualan);
    const orderStatus = @json($statusPesanan);

    // Render Grafik Batang (Sales)
    new Chart(document.getElementById('chartPenjualan'), {
        type: 'bar',
        data: {
            labels: salesData.map(d => d.bulan),
            datasets: [{
                data: salesData.map(d => d.total),
                backgroundColor: 'rgba(200, 16, 16, 0.15)', // Warna merah transparan khas logo #C81010
                borderColor: '#C81010',                      // Warna merah solid Runora
                borderWidth: 1.5,
                borderRadius: 6,
                barPercentage: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#F9FAFB' }, ticks: { font: { size: 10, family: 'Poppins' } } },
                x: { grid: { display: false }, ticks: { font: { size: 10, family: 'Poppins' } } }
            }
        }
    });

    // Render Grafik Lingkaran (Order Status)
    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: ['Belum Bayar', 'Diproses', 'Dikirim', 'Selesai'],
            datasets: [{
                data: [
                    orderStatus.menunggu_pembayaran || 0,
                    orderStatus.diproses || 0,
                    orderStatus.dikirim || 0,
                    orderStatus.selesai || 0
                ],
                backgroundColor: ['#EAB308', '#3B82F6', '#22C55E', '#A855F7'], // Menggunakan standar warna palette Tailwind-500
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 8, padding: 12, font: { size: 10, family: 'Poppins', weight: 500 } } }
            }
        }
    });
});
</script>
@endpush