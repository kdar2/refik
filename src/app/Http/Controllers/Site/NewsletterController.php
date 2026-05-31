<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'name'  => ['nullable', 'string', 'max:255'],
        ]);

        // Aynı e-posta yeniden abone oluyorsa kaydı yenile (unsubscribed_at temizle).
        $sub = NewsletterSubscriber::firstOrNew(['email' => $data['email']]);
        $sub->fill([
            'name'              => $data['name'] ?? $sub->name,
            'language'          => app()->getLocale(),
            'is_active'         => true,
            'verification_token'=> $sub->verification_token ?? Str::random(40),
            'unsubscribed_at'   => null,
        ])->save();

        return back()->with('newsletter_success', 'Bültenimize başarıyla abone oldunuz, teşekkürler!');
    }
}
