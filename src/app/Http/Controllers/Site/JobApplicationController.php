<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    public function show(): View
    {
        return view('pages.careers');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name'   => ['required', 'string', 'max:120'],
            'email'       => ['required', 'email', 'max:255'],
            'phone'       => ['required', 'string', 'max:30'],
            'position'    => ['required', 'string', 'max:120'],
            'cover_letter'=> ['nullable', 'string', 'max:5000'],
            'cv'          => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // 5 MB
            'kvkk'        => ['accepted'],
        ], [
            'cv.required'   => 'CV yükleyin (PDF/DOC/DOCX, en fazla 5 MB).',
            'cv.mimes'      => 'CV dosyası PDF, DOC veya DOCX olmalıdır.',
            'kvkk.accepted' => 'Devam etmek için KVKK aydınlatma metnini onaylamanız gerekir.',
        ]);

        $cvPath = $request->file('cv')->store('cvs', 'public');

        JobApplication::create([
            'full_name'    => $data['full_name'],
            'email'        => $data['email'],
            'phone'        => $data['phone'],
            'position'     => $data['position'],
            'cover_letter' => $data['cover_letter'] ?? null,
            'cv_path'      => $cvPath,
            'status'       => 'pending',
        ]);

        return back()->with('career_success', 'Başvurunuz alındı; uygunluk durumunda sizinle iletişime geçeceğiz.');
    }
}
