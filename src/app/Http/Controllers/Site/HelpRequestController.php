<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\HelpRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HelpRequestController extends Controller
{
    public function show(): View
    {
        return view('pages.help-request');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name'   => ['required', 'string', 'max:120'],
            'email'       => ['nullable', 'email', 'max:255'],
            'phone'       => ['required', 'string', 'max:30'],
            'city'        => ['required', 'string', 'max:80'],
            'district'    => ['required', 'string', 'max:80'],
            'category'    => ['required', Rule::in(['gida', 'saglik', 'barinma', 'egitim', 'giyim', 'diger'])],
            'description' => ['required', 'string', 'max:3000'],
            'kvkk'        => ['accepted'],
        ], [
            'kvkk.accepted' => 'Devam etmek için KVKK aydınlatma metnini onaylamanız gerekir.',
        ]);

        unset($data['kvkk']);
        HelpRequest::create($data + ['status' => 'pending']);

        return back()->with('help_success', 'Yardım talebiniz alındı; ekibimiz en kısa sürede iletişime geçecek.');
    }
}
