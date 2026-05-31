@extends('admin.layout')

@section('title', 'Site Ayarları')
@section('header', 'Site Ayarları')

@section('content')

<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
    @csrf
    @method('PUT')

    @php
        $groupLabels = [
            'site'       => 'Site Bilgileri',
            'social'     => 'Sosyal Medya',
            'efficiency' => 'Verimlilik (%)',
            'alert'      => 'Acil Duyuru Çubuğu',
            'legal'      => 'Yasal Kayıtlar',
            'currency'   => 'Para Birimi',
            'general'    => 'Genel',
        ];
        $groupIcons = [
            'site' => 'building-2', 'social' => 'share-2', 'efficiency' => 'chart-pie',
            'alert' => 'bell-ring', 'legal' => 'shield', 'currency' => 'banknote', 'general' => 'settings',
        ];
    @endphp

    @foreach ($groups as $group => $items)
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-3">
                <span class="grid place-items-center w-9 h-9 rounded-lg bg-brand-50 text-brand-700">
                    <i data-lucide="{{ $groupIcons[$group] ?? 'settings' }}" class="w-4 h-4"></i>
                </span>
                <h2 class="font-bold text-brand-900">{{ $groupLabels[$group] ?? ucfirst($group) }}</h2>
            </div>
            <div class="p-5 space-y-4">
                @foreach ($items as $s)
                    @php
                        $name = "settings[{$s->key}]";
                        $current = old("settings.{$s->key}", $s->value);
                    @endphp
                    <div class="grid sm:grid-cols-[260px_1fr] gap-3 items-start">
                        <label class="text-sm">
                            <span class="font-bold text-brand-900 block">{{ $s->key }}</span>
                            <span class="text-xs text-slate-500">{{ $s->type }}</span>
                        </label>
                        <div>
                            @if ($s->type === 'bool')
                                <input type="hidden" name="{{ $name }}" value="0">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="{{ $name }}" value="1"
                                           @checked((bool) $current)
                                           class="h-4 w-4 rounded border-slate-300 text-brand-700">
                                    <span class="text-sm text-slate-700">Aktif</span>
                                </label>
                            @elseif (in_array($s->type, ['int']))
                                <input type="number" name="{{ $name }}" value="{{ $current }}" class="input !py-2">
                            @elseif (str_contains($s->key, 'address') || str_contains($s->key, 'text'))
                                <textarea name="{{ $name }}" rows="2" class="input resize-none !py-2">{{ $current }}</textarea>
                            @else
                                <input type="text" name="{{ $name }}" value="{{ $current }}" class="input !py-2">
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <div class="flex justify-end">
        <button type="submit" class="btn-primary btn-md shadow-brand">
            <i data-lucide="save" class="w-4 h-4"></i> Tüm Ayarları Kaydet
        </button>
    </div>
</form>

@endsection
