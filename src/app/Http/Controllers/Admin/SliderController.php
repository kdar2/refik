<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SliderController extends Controller
{
    public function index(): View
    {
        return view('admin.sliders.index', [
            'sliders' => Slider::orderBy('order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.sliders.form', [
            'slider' => new Slider(['is_active' => true, 'overlay_color' => '#0B295C', 'overlay_opacity' => 40, 'order' => 0]),
            'mode'   => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Slider::create($this->validated($request));
        return redirect()->route('admin.sliders.index')->with('success', 'Slayt eklendi.');
    }

    public function edit(Slider $slider): View
    {
        return view('admin.sliders.form', ['slider' => $slider, 'mode' => 'edit']);
    }

    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $slider->update($this->validated($request));
        return redirect()->route('admin.sliders.index')->with('success', 'Slayt güncellendi.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        $slider->delete();
        return back()->with('success', 'Slayt silindi.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'eyebrow_tr'      => ['nullable', 'string', 'max:255'],
            'title_tr'        => ['required', 'string', 'max:255'],
            'subtitle_tr'     => ['nullable', 'string', 'max:1000'],
            'image'           => ['required', 'string', 'max:500'],
            'cta_text_tr'     => ['nullable', 'string', 'max:120'],
            'cta_url'         => ['nullable', 'string', 'max:500'],
            'overlay_color'   => ['required', 'string', 'size:7'],
            'overlay_opacity' => ['required', 'integer', 'min:0', 'max:100'],
            'order'           => ['required', 'integer'],
            'is_active'       => ['nullable', 'boolean'],
        ]);
    }
}
