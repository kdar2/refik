<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $groups = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.settings.index', compact('groups'));
    }

    public function update(Request $request): RedirectResponse
    {
        $values = $request->input('settings', []);

        foreach ($values as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if (!$setting) continue;

            // Bool için checkbox handling
            $stored = $setting->type === 'bool'
                ? ($value ? '1' : '0')
                : (string) $value;

            $setting->update(['value' => $stored]);
        }

        return back()->with('success', 'Ayarlar güncellendi.');
    }
}
