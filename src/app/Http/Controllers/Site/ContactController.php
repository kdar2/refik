<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('pages.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email'     => ['required', 'email', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:30'],
            'subject'   => ['required', 'string', 'max:200'],
            'message'   => ['required', 'string', 'max:3000'],
            'kvkk'      => ['accepted'],
        ], [
            'kvkk.accepted' => 'Devam etmek için KVKK aydınlatma metnini onaylamanız gerekir.',
        ]);

        unset($data['kvkk']);
        ContactMessage::create($data);

        return back()->with('contact_success', 'Mesajınız alındı, en kısa sürede sizinle iletişime geçeceğiz.');
    }
}
