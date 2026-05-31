<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VolunteerController extends Controller
{
    public function show(): View
    {
        return view('pages.volunteer');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name'   => ['required', 'string', 'max:120'],
            'email'       => ['required', 'email', 'max:255'],
            'phone'       => ['required', 'string', 'max:30'],
            'city'        => ['required', 'string', 'max:80'],
            'birth_date'  => ['required', 'date', 'before:today'],
            'areas'       => ['required', 'array', 'min:1'],
            'areas.*'     => ['string', 'max:60'],
            'experience'  => ['nullable', 'string', 'max:2000'],
            'kvkk'        => ['accepted'],
        ], [
            'areas.required' => 'En az bir ilgi alanı seçin.',
            'kvkk.accepted'  => 'Devam etmek için KVKK aydınlatma metnini onaylamanız gerekir.',
        ]);

        unset($data['kvkk']);
        Volunteer::create($data + ['status' => 'pending']);

        return back()->with('volunteer_success', 'Gönüllü başvurunuz alındı; ekibimiz değerlendirip sizinle iletişime geçecektir.');
    }
}
