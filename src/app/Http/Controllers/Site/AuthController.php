<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private readonly ApiService $api)
    {
    }

    // ---------------------------------------------------------------
    // Giriş
    // ---------------------------------------------------------------

    public function loginForm(): View
    {
        return view('pages.auth.login');
    }

    public function login(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $result = $this->api->post('/api/v1/auth/login', $data);

        $token = data_get($result, 'token') ?? data_get($result, 'access');

        if (!$token) {
            $error = data_get($result, 'detail') ?? data_get($result, 'message') ?? 'Giriş başarısız. E-posta veya şifre hatalı.';

            if ($request->wantsJson()) {
                return response()->json(['error' => $error], 401);
            }

            return back()->withInput($request->except('password'))->with('auth_error', $error);
        }

        session([
            'api_token'  => $token,
            'api_user'   => data_get($result, 'user', []),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->intended('/')->with('auth_status', 'Giriş yapıldı.');
    }

    // ---------------------------------------------------------------
    // Kayıt
    // ---------------------------------------------------------------

    public function registerForm(): View
    {
        return view('pages.auth.register');
    }

    public function register(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:191'],
            'email'                 => ['required', 'email', 'max:191'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'phone'                 => ['nullable', 'string', 'max:20'],
        ]);

        $result = $this->api->post('/api/v1/auth/register', $data);

        $token = data_get($result, 'token') ?? data_get($result, 'access');

        if (!$token) {
            $error = data_get($result, 'detail') ?? data_get($result, 'message') ?? 'Kayıt sırasında bir hata oluştu.';

            if ($request->wantsJson()) {
                return response()->json(['error' => $error], 422);
            }

            return back()->withInput($request->except('password', 'password_confirmation'))->with('auth_error', $error);
        }

        session([
            'api_token' => $token,
            'api_user'  => data_get($result, 'user', []),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect('/')->with('auth_status', 'Hesabınız oluşturuldu.');
    }

    // ---------------------------------------------------------------
    // Çıkış
    // ---------------------------------------------------------------

    public function logout(Request $request): RedirectResponse|JsonResponse
    {
        // API'ye çıkış bildir (token hâlâ session'da olduğu için API çağrısı çalışır)
        $this->api->post('/api/v1/auth/logout');

        $request->session()->forget(['api_token', 'api_user']);
        $request->session()->regenerate();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect('/')->with('auth_status', 'Çıkış yapıldı.');
    }

    // ---------------------------------------------------------------
    // Guest token
    // ---------------------------------------------------------------

    public function guestToken(Request $request): JsonResponse
    {
        $this->api->ensureGuestToken();

        return response()->json([
            'success' => (bool) session('api_token'),
        ]);
    }
}
