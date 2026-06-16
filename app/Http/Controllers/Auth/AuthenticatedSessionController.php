<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        // 1. Validasi email & password via Laravel Breeze
        $request->authenticate();

        // 2. Regenerasi session keamanan
        $request->session()->regenerate();

        // 3. Jika request datang dari AJAX / Fetch JavaScript (Kondisi Login Kamu)
        if ($request->wantsJson()) {
            $user = Auth::user();
        
            if ($user->role === 'admin') {
                $redirectUrl = route('admin.dashboard');
            } else {
                $redirectUrl = url('/'); // Pembeli langsung diarahkan ke Dashboard Public (Welcome)
            }

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil!',
                'redirect' => $redirectUrl
            ]);
        }

        // 4. Fallback jika login dilakukan secara normal tanpa AJAX
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->to('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}