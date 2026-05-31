<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.index', [
            'pages' => Page::orderBy('title_tr')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.pages.form', ['page' => new Page(['is_published' => true]), 'mode' => 'create']);
    }

    public function store(Request $request): RedirectResponse
    {
        Page::create($this->validated($request));
        return redirect()->route('admin.pages.index')->with('success', 'Sayfa oluşturuldu.');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.form', ['page' => $page, 'mode' => 'edit']);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $page->update($this->validated($request));
        return back()->with('success', 'Sayfa güncellendi.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Sayfa silindi.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title_tr'     => ['required', 'string', 'max:255'],
            'title_en'     => ['nullable', 'string', 'max:255'],
            'body_tr'      => ['required', 'string'],
            'body_en'      => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);
    }
}
