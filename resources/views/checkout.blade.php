@extends('layouts.app')

@section('title', 'Proses Checkout')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">FORMULIR CHECKOUT</h2>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
        <p class="text-gray-600 mb-4">Sip! Data barang yang kamu pilih di keranjang tadi sudah berhasil lolos dikirim ke sini via session, Sekar.</p>
        
        <h5 class="font-bold text-gray-800 mb-2">Ringkasan Barang yang akan kamu beli:</h5>
        <ul class="list-disc pl-5 text-sm text-gray-700">
            @foreach($checkoutItems as $item)
                <li>{{ $item->product->name }} (Ukuran: {{ $item->size ?? '-' }}) - {{ $item->quantity }} pcs</li>
            @endforeach
        </ul>
        
        <div class="mt-6 text-sm text-gray-400">Next, tinggal kita buatin form input Alamat Pengiriman, Nomor HP, dan tombol Bayar Sekarang!</div>
    </div>
</div>
@endsection