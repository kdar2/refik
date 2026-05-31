<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email'     => ['required', 'email', 'max:255'],
            'phone'     => ['required', 'string', 'max:30'],
            'date'      => ['required', 'date', 'after_or_equal:today'],
            'time'      => ['required', 'date_format:H:i'],
            'topic'     => ['required', Rule::in(['donation_advisory', 'zakat_advisory', 'will', 'general'])],
            'notes'     => ['nullable', 'string', 'max:1000'],
        ]);

        Appointment::create([
            ...$data,
            'status' => 'pending',
        ]);

        return back()
            ->with('appointment_success', 'Randevu talebiniz alındı; en kısa sürede sizinle iletişime geçeceğiz.')
            ->withFragment('zekat-hesapla');
    }
}
