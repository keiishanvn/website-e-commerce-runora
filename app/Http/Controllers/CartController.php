<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;       
use App\Models\OrderItem;
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

// 2. Tambah ke Keranjang
    public function store(Request $request)
    {
        // 1. Cek status login user secara manual untuk kebutuhan AJAX
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'AUTH_EXPIRED'
            ], 401);
        }

        // 2. Validasi inputan request
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'kuantitas'  => 'required|integer|min:1',
        ]);

        $userId = Auth::id();

        // 3. Cek apakah produk ini sudah ada di keranjang user
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

        // 4. Hitung total item unik untuk update badge navbar
        $cartCount = Cart::where('user_id', $userId)->count();

        // 5. Kembalikan response JSON sukses
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

    // 5. Proses Validasi Checklist Halaman Keranjang -> Simpan Ke Session
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

        $formattedItems = [];
        foreach ($selectedCarts as $item) {
            $obj = new \stdClass();
            $obj->id = $item->id;
            $obj->kuantitas = (int) $item->kuantitas;
            $obj->product = $item->product; 
            $formattedItems[] = $obj;
        }

        session(['checkout_items' => $formattedItems]);

        return redirect()->route('checkout.index');
    }

    // 6. Tampilan Halaman Pembayaran Akhir
    public function checkoutIndex()
    {
        $sessionItems = session('checkout_items', []);
        $checkoutItems = collect($sessionItems);

        if ($checkoutItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Silakan pilih produk di keranjang terlebih dahulu.');
        }

        return view('payment', compact('checkoutItems'));
    }

    // 7. Jalur Instan "Beli Sekarang" dari halaman detail produk
    public function beliSekarang(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'kuantitas'  => 'required|integer|min:1',
        ]);

        $itemInstan = new \stdClass();
        $itemInstan->id = 0; 
        $itemInstan->kuantitas = (int) $request->kuantitas;
        $itemInstan->product = Product::findOrFail($request->product_id);

        session(['checkout_items' => [$itemInstan]]);

        return redirect()->route('checkout.index');
    }

    public function process(Request $request)
    {
        try {
            $input = $request->isJson() ? $request->json()->all() : $request->all();

            $metodePembayaran = $input['metode_pembayaran'] ?? 'transfer_bank';
            $alamatPengiriman = !empty($input['alamat_pengiriman']) ? $input['alamat_pengiriman'] : 'Alamat Pengguna RUNORA';
            $noHp             = !empty($input['no_hp']) ? $input['no_hp'] : '08123456789';
            $cartIds          = $input['cart_ids'] ?? [];

            $userId = Auth::id();
            $totalHarga = 0;
            $itemsToProcess = [];
            $cartIdsToDelete = [];

            // CEK LEWAT DATABASE UTAMA (CHECKOUT DARI KERANJANG) 
            if (!empty($cartIds)) {
                $cleanCartIds = array_map('intval', $cartIds);
                $cartItems = Cart::with('product')->whereIn('id', $cleanCartIds)->where('user_id', $userId)->get();
                
                foreach ($cartItems as $cart) {
                    if ($cart->product) {
                        $totalHarga += (float)$cart->product->harga * (int)$cart->kuantitas;
                        $itemsToProcess[] = [
                            'product_id'   => $cart->product_id,
                            'kuantitas'    => $cart->kuantitas,
                            'harga_satuan' => $cart->product->harga
                        ];
                        $cartIdsToDelete[] = $cart->id;
                    }
                }
            }

            // JALUR BACKUP (JIKA BELI SEKARANG / DATABASE KOSONG) 
            if (empty($itemsToProcess)) {
                $sessionItems = session('checkout_items', []);
                
                foreach ($sessionItems as $item) {
                    $prodId = isset($item->product->id) ? $item->product->id : (isset($item->product['id']) ? $item->product['id'] : null);
                    $kuantitas = isset($item->kuantitas) ? (int)$item->kuantitas : 1;
                    
                    if ($prodId) {
                        $dbProduct = Product::find($prodId);
                        if ($dbProduct) {
                            $totalHarga += (float)$dbProduct->harga * $kuantitas;
                            $itemsToProcess[] = [
                                'product_id'   => $dbProduct->id,
                                'kuantitas'    => $kuantitas,
                                'harga_satuan' => $dbProduct->harga
                            ];
                            
                            if (isset($item->id) && $item->id > 0) {
                                $cartIdsToDelete[] = $item->id;
                            }
                        }
                    }
                }
            }

            if (empty($itemsToProcess)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sistem tidak menemukan produk aktif di keranjang atau session belanja kamu sudah kedaluwarsa.'
                ], 400);
            }

            // Bikin Nomor Invoice 
            $uniqueInvoice = 'RUNORA-' . date('YmdHis') . '-' . rand(1000, 9999);

            $order = Order::create([
                'user_id'        => $userId,
                'invoice_number' => $uniqueInvoice,
                'total_harga'    => $totalHarga,
                'status'         => '0', 
                'alamat'         => $alamatPengiriman,
                'no_hp'          => $noHp,
                'notes'          => '-', 
            ]);

            foreach ($itemsToProcess as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['product_id'],
                    'kuantitas'    => $item['kuantitas'],
                    'harga'        => $item['harga_satuan'],
                ]);
            }

            if (!empty($cartIdsToDelete)) {
                Cart::whereIn('id', $cartIdsToDelete)->where('user_id', $userId)->delete();
            }
            session()->forget('checkout_items');

            return response()->json([
                'success' => true,
                'message' => 'Transaksi sukses dicatat ke database!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Eror Database: ' . $e->getMessage()
            ], 500);
        }
    }

    // 9. KONEKSI DATA NYATA: Menampilkan halaman riwayat dari database asli 
    public function riwayatIndex()
    {
        $orders = Order::where('user_id', Auth::id())
                       ->orderBy('created_at', 'desc')
                       ->get();

        return view('riwayat', compact('orders'));
    }

    // 10. Menampilkan Halaman Pengaturan Akun
    public function settingsIndex()
    {
        return view('settings');
    }

    // 11. Memproses Update Data Profil Pengguna
    public function settingsUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Data profil Anda berhasil diperbarui!');
    }
}