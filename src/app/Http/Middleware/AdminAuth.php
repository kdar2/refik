<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sadece role=admin/editor kullanıcıların admin paneline erişimine izin verir.
 * Misafir → /admin/login'e yönlenir, intended URL session'a kaydedilir.
 * Yetkisiz giriş → 403.
 */
class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->guest(route('admin.login'));
        }

        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'editor'], true)) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return $next($request);
    }
}
