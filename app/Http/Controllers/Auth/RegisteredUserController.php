<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
    // 1. Validasi Inputan Sesuai Form Kamu
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'], // Validasi no telp
        ]);

    // 2. Simpan Data ke Database (Otomatis role set ke pembeli)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'pembeli', // <--- Dikunci jadi pembeli demi keamanan sistem!
        ]);

    // 3. Response AJAX JSON agar klop dengan JavaScript di Blade
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil!',
                'redirect' => route('login') // Diarahkan ke login setelah sukses
            ]);
        }

    // Fallback jika browser tidak mendukung AJAX
        event(new Registered($user));
        Auth::login($user);
        return redirect(RouteServiceProvider::HOME);
    }
}
