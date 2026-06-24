@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container-fluid px-0 mb-0" style="font-family: 'Inter', -apple-system, sans-serif;">
    <div class="row g-0 d-flex align-items-stretch" style="min-height: calc(100vh - 70px); opacity: 1;">
        <div class="col-md-3 bg-white d-flex flex-column justify-content-between shadow-sm" 
             style="max-width: 280px; border-right: 4px solid #dee2e6 !important;">
            <div>

                <div class="d-flex align-items-center gap-3 px-4 py-4" style="background-color: #b91c1c;">
                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center overflow-hidden" style="width: 55px; height: 55px; flex-shrink: 0;">
                        <i class="fas fa-user text-dark fs-3"></i>
                    </div>
                    <div class="min-w-0 text-white">
                        <h6 class="fw-bold mb-0 text-truncate" style="font-size: 0.95rem; letter-spacing: 0.2px;">{{ Auth::user()->name }}</h6>
                        <p class="mb-0 small text-truncate opacity-85" style="font-size: 0.8rem;">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                // Navigasi Menu Vertikal
                <div class="nav flex-column border-bottom border-secondary-subtle">
                    <a href="{{ route('riwayat.pesanan') }}" class="nav-link text-dark fw-bold px-4 py-3 d-flex align-items-center gap-3 text-decoration-none border-bottom border-secondary-subtle bg-light" style="font-size: 0.85rem;">
                        <i class="fas fa-history fs-5"></i> Riwayat Pesanan
                    </a>
                    <a href="{{ route('user.settings') }}" class="nav-link text-dark fw-bold px-4 py-3 d-flex align-items-center gap-3 text-decoration-none bg-white btn-menu-pasif" style="font-size: 0.85rem;">
                        <i class="far fa-user fs-5"></i> Pengaturan Akun
                    </a>
                </div>
            </div>

            <div class="px-4 py-4 my-4 d-flex justify-content-start">
                <form action="{{ route('logout') }}" method="POST" class="w-auto">
                    @csrf
                    <button type="submit" class="btn btn-outline-dark bg-white text-dark rounded-3 fw-bold px-4 py-2 shadow-sm btn-logout-outline" style="font-size: 0.85rem; border: 1px solid #212529; min-width: 110px;">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        //2. KONTEN SEBELAH KANAN: TABEL DATA RIWAYAT PESANAN 
        <div class="col-md-9 flex-grow-1 bg-white px-5 py-5">
            
            // Judul Halaman 
            <h2 class="fw-black text-dark text-uppercase tracking-tight mb-5 fs-4" style="letter-spacing: 0.3px;">RIWAYAT PESANAN</h2>

            // Struktur Tabel Minimalis
            <div class="table-responsive">
                <table class="table align-middle border-0 table-hover m-0">
                    
                    <thead>
                        <tr class="text-secondary border-bottom border-2 border-light fs-7" style="font-size: 0.8rem; font-weight: 700; letter-spacing: 0.5px;">
                            <th class="pb-3 border-0" style="width: 30%;">No. Pesanan</th>
                            <th class="pb-3 border-0 text-center" style="width: 25%;">Total</th>
                            <th class="pb-3 border-0 text-center" style="width: 25%;">Status</th>
                            <th class="pb-3 border-0 text-end" style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="border-0">
                        @forelse ($orders as $order)
                            <tr class="border-bottom border-light">
                                <td class="py-4 font-monospace fw-bold text-dark fs-6">
                                    #{{ str_replace('RUNORA-', '', $order->invoice_number) }}
                                </td>

                                <td class="py-4 text-center fw-semibold text-secondary-dark fs-6 whitespace-nowrap">
                                    Rp. {{ number_format($order->total_harga, 0, ',', '.') }}
                                </td>

                                <td class="py-4 text-center">
                                    @php
                                        $statusText = 'Diproses';
                                        $badgeColor = 'text-warning';

                                        if($order->status == '1' || $order->status == 'Selesai' || $order->status == 'success' || $order->status == 'completed') {
                                            $statusText = 'Selesai';
                                            $badgeColor = 'text-success';
                                        } elseif($order->status == 'Dikirim' || $order->status == 'shipping') {
                                            $statusText = 'Dikirim';
                                            $badgeColor = 'text-primary';
                                        }
                                    @endphp
                                    <div class="d-inline-block border border-secondary-subtle rounded px-3 py-1 bg-white fw-bold text-center {{ $badgeColor }}" 
                                         style="min-width: 100px; font-size: 0.75rem; letter-spacing: 0.2px;">
                                        {{ $statusText }}
                                    </div>
                                </td>

                                <td class="py-4 text-end">
                                    <a href="#" class="btn btn-sm btn-white border border-secondary text-dark fw-bold rounded-2 px-3 py-1.5 shadow-sm btn-detail-action" 
                                       style="font-size: 0.75rem; letter-spacing: 0.2px;">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 border-0 text-muted">
                                    <i class="fas fa-history d-block mb-3 text-light-dark" style="font-size: 2.5rem;"></i>
                                    <p class="fw-medium mb-0 small">Belum ada riwayat pesanan yang tercatat pada akun ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

    </div>
</div>

<style>
    .btn-menu-pasif {
        transition: background-color 0.15s ease;
    }
    .btn-menu-pasif:hover {
        background-color: #f8f9fa !important;
    }
    .btn-logout-outline {
        transition: all 0.2s ease-in-out;
    }
    .btn-logout-outline:hover {
        background-color: #212529 !important;
        color: #ffffff !important;
    }
    .btn-detail-action {
        transition: all 0.15s ease-in-out;
    }
    .btn-detail-action:hover {
        background-color: #f8f9fa !important;
        border-color: #212529 !important;
    }
</style>
@endsection