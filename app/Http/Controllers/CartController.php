<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // 1. Tampilkan Halaman Keranjang
    public function index()
    {
        $carts = Cart::with('product')
                     ->where('user_id', Auth::id())
                     ->get();

        return view('cart', compact('carts')); 
    }

    // 2. Tambah ke Keranjang (Murni AJAX JSON - Tanpa Ukuran)
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'kuantitas'  => 'required|integer|min:1',
        ]);

        $userId = Auth::id();

        // Cek apakah produk ini sudah ada di keranjang user
        $cart = Cart::where('user_id', $userId)
                    ->where('product_id', $request->product_id)
                    ->first();

        if ($cart) {
            // Jika sudah ada, naikkan jumlah kuantitasnya
            $cart->increment('kuantitas', $request->kuantitas);
        } else {
            // Jika belum ada, buat data baru
            Cart::create([
                'user_id'    => $userId,
                'product_id' => $request->product_id,
                'kuantitas'  => $request->kuantitas, 
            ]);
        }

        // Hitung total item unik untuk update badge navbar
        $cartCount = Cart::where('user_id', $userId)->count();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil masuk keranjang belanja RUNORA!',
            'cart_count' => $cartCount
        ]);
    }

    // 3. Update Kuantitas via AJAX Plus Minus di Halaman Keranjang
    public function update(Request $request, $id)
    {
        $request->validate(['kuantitas' => 'required|integer|min:1']);

        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->update(['kuantitas' => $request->kuantitas]);

        return response()->json([
            'success' => true,
            'message' => 'Kuantitas berhasil diperbarui.'
        ]);
    }

    // 4. Hapus Satu Item dari Keranjang
    public function destroy($id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus.');
    }

    // 5. Proses Validasi Checklist Checkout (Simpan Ke Session)
    public function checkout(Request $request)
    {
        $request->validate([
            'selected' => 'required|array|min:1',
            'selected.*' => 'exists:carts,id',
        ]);

        $selectedCarts = Cart::with('product')
                             ->whereIn('id', $request->selected)
                             ->where('user_id', Auth::id())
                             ->get();

        session(['checkout_items' => $selectedCarts]);

        return redirect()->route('checkout.index');
    }

    // 6. Tampilan Halaman Formulir Checkout Alamat
    public function checkoutIndex()
    {
        $checkoutItems = session('checkout_items');

        if (!$checkoutItems || $checkoutItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Silakan pilih produk di keranjang terlebih dahulu.');
        }

        return view('checkout', compact('checkoutItems'));
    }
}