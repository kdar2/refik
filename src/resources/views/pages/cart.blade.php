@extends('layouts.app')

@section('title', 'Bağış Sepeti')

@section('content')

<section class="section bg-surface-alt min-h-[60vh]">
    <div class="container-x max-w-4xl">

        <div class="text-center mb-8" data-rise>
            <p class="h-eyebrow">Hayra giden adımlar</p>
            <h1 class="mt-2 text-3xl lg:text-4xl font-extrabold font-display text-brand-900 tracking-tight">
                Bağış Sepetin
            </h1>
            <p class="mt-3 text-slate-500 text-sm max-w-xl mx-auto">
                Birden fazla niyetini aynı sepette toplayabilir, tek seferde gönderebilirsin.
            </p>
        </div>

        @if (session('cart_status'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 mb-6 text-sm text-emerald-900 flex items-center gap-2">
                <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                {{ session('cart_status') }}
            </div>
        @endif

        @if ($count === 0)
            <div class="rounded-3xl bg-white border border-slate-100 shadow-sm p-10 text-center">
                <div class="mx-auto mb-4 grid place-items-center w-16 h-16 rounded-full bg-brand-50 text-brand-700">
                    <i data-lucide="shopping-bag" class="w-8 h-8"></i>
                </div>
                <h2 class="text-xl font-bold font-display text-brand-900 mb-2">Sepetin henüz boş</h2>
                <p class="text-sm text-slate-500 mb-6">Kampanyalardan veya hızlı bağış formundan sepetine ekleme yapabilirsin.</p>
                <a href="{{ route('campaigns.index') }}" class="btn-primary btn-md">
                    <i data-lucide="heart-handshake" class="w-4 h-4"></i>
                    Kampanyaları Gör
                </a>
            </div>
        @else
            <div class="rounded-3xl bg-white border border-slate-100 shadow-brand overflow-hidden">

                <ul class="divide-y divide-slate-100">
                    @foreach ($items as $id => $item)
                        @php
                            $meta     = config("currencies.meta.{$item['currency']}", config('currencies.meta.TRY'));
                            $symbol   = $meta['symbol'] ?? '₺';
                            $position = $meta['symbol_position'] ?? 'after';
                            $amount   = number_format(
                                (float) $item['amount'],
                                $meta['decimals']      ?? 2,
                                $meta['decimal_sep']   ?? ',',
                                $meta['thousands_sep'] ?? '.',
                            );
                            $amountStr = $position === 'before' ? "$symbol$amount" : "$amount $symbol";
                            $typeLabels = [
                                'general' => 'Genel Bağış', 'zakat' => 'Zekat', 'fitre' => 'Fitre',
                                'sadaka' => 'Sadaka-i Cariye', 'kurban' => 'Kurban', 'adak' => 'Adak', 'kefaret' => 'Kefaret',
                            ];
                            $freqLabels = config('currencies.frequencies', []);
                        @endphp
                        <li class="px-6 lg:px-8 py-5 flex items-center gap-4">
                            <div class="grid place-items-center w-12 h-12 shrink-0 rounded-xl bg-brand-50 text-brand-700">
                                <i data-lucide="heart-handshake" class="w-5 h-5"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-brand-900 truncate">
                                    {{ $item['campaign_title'] ?? ($typeLabels[$item['type']] ?? 'Bağış') }}
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5 flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <span>{{ $typeLabels[$item['type']] ?? $item['type'] }}</span>
                                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                    <span>{{ $freqLabels[$item['frequency']] ?? $item['frequency'] }}</span>
                                    @if (!empty($item['intention']))
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        <span>Niyet: {{ $item['intention'] }}</span>
                                    @endif
                                </p>
                            </div>

                            <p class="text-base font-extrabold text-brand-900 shrink-0">{{ $amountStr }}</p>

                            <form method="POST" action="{{ route('cart.remove', $id) }}" class="shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="grid place-items-center w-9 h-9 rounded-lg border border-slate-200 text-slate-500 hover:text-rose-600 hover:border-rose-200 transition" aria-label="Kaldır">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>

                <div class="px-6 lg:px-8 py-5 bg-brand-50 border-t border-brand-100 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-wider text-brand-500 font-semibold">Toplam</p>
                        <p class="text-2xl font-extrabold font-display text-brand-900">{{ $total }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <form method="POST" action="{{ route('cart.clear') }}">
                            @csrf
                            <button type="submit" class="btn-ghost btn-md">
                                <i data-lucide="x" class="w-4 h-4"></i>
                                Sepeti Temizle
                            </button>
                        </form>
                        <a href="{{ route('donate.show') }}" class="btn-accent btn-md shadow-brand">
                            <i data-lucide="heart" class="w-4 h-4"></i>
                            Bağışı Tamamla
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </div>
</section>

@endsection
