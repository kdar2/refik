@extends('layouts.app')

@section('title', 'Çalışmalarımız')

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-brand-700 via-brand-800 to-brand-900 text-white">
    <div class="absolute inset-0 bg-grid-soft opacity-30 pointer-events-none"></div>
    <div class="container-x py-16 lg:py-20 relative">
        <p class="h-eyebrow text-brand-200">Çalışmalarımız</p>
        <h1 class="mt-2 text-4xl lg:text-5xl font-extrabold font-display tracking-tight text-balance">
            Bağışlarınızla Hayatlara Dokunan Kampanyalar
        </h1>
        <p class="mt-4 text-brand-100/90 max-w-2xl">
            14 farklı kategoride yürüttüğümüz çalışmalarda birlikte hareket edelim. İhtiyacın olduğu yere ulaşmak için tek bir tıklama yetiyor.
        </p>
    </div>
</section>

{{-- İçerik: filtre + grid --}}
<section class="section">
    <div class="container-x grid lg:grid-cols-[280px_1fr] gap-8">

        {{-- Filtre paneli --}}
        <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
            <form method="GET" action="{{ route('campaigns.index') }}" class="space-y-6 rounded-2xl bg-white border border-slate-100 shadow-sm p-5">
                <div>
                    <h3 class="text-xs uppercase tracking-wider font-semibold text-brand-500 mb-3">Sıralama</h3>
                    <select name="sort" class="input !py-2.5">
                        <option value="featured"    @selected($sort==='featured')>Öne çıkanlar</option>
                        <option value="newest"      @selected($sort==='newest')>En yeni</option>
                        <option value="most-donated"@selected($sort==='most-donated')>En çok bağış toplayan</option>
                        <option value="ending-soon" @selected($sort==='ending-soon')>Bitime az kalan</option>
                    </select>
                </div>

                <div>
                    <h3 class="text-xs uppercase tracking-wider font-semibold text-brand-500 mb-3">Bölge</h3>
                    <div class="space-y-1.5 text-sm">
                        @foreach (['' => 'Tümü', 'yurtici' => 'Yurtiçi', 'yurtdisi' => 'Yurtdışı'] as $val => $label)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="region" value="{{ $val }}" @checked(($activeRegion ?? '') === $val)
                                       class="h-4 w-4 text-brand-700 border-slate-300 focus:ring-brand-300">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-xs uppercase tracking-wider font-semibold text-brand-500 mb-3">Bağış Türü Uygunluğu</h3>
                    <div class="space-y-1.5 text-sm">
                        @foreach (['' => 'Tümü', 'zakat' => 'Zekat', 'sadaka' => 'Sadaka-i Cariye', 'fitre' => 'Fitre', 'kurban' => 'Kurban'] as $val => $label)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="eligibility" value="{{ $val }}" @checked(($activeEligibility ?? '') === $val)
                                       class="h-4 w-4 text-brand-700 border-slate-300 focus:ring-brand-300">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-xs uppercase tracking-wider font-semibold text-brand-500 mb-3">Kategori</h3>
                    <div class="space-y-1 max-h-72 overflow-y-auto pr-1 scrollbar-none">
                        <label class="flex items-center gap-2 cursor-pointer text-sm">
                            <input type="radio" name="category" value="" @checked(empty($activeCategory))
                                   class="h-4 w-4 text-brand-700 border-slate-300 focus:ring-brand-300">
                            <span class="font-semibold">Tüm kategoriler</span>
                        </label>
                        @foreach ($categories as $cat)
                            <label class="flex items-center gap-2 cursor-pointer text-sm">
                                <input type="radio" name="category" value="{{ $cat->slug }}" @checked($activeCategory === $cat->slug)
                                       class="h-4 w-4 text-brand-700 border-slate-300 focus:ring-brand-300">
                                <span>{{ $cat->name_tr }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="submit" class="btn-primary btn-md flex-1 justify-center">
                        <i data-lucide="filter" class="w-4 h-4"></i> Uygula
                    </button>
                    <a href="{{ route('campaigns.index') }}" class="btn-ghost btn-md" title="Filtreleri temizle">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                </div>
            </form>
        </aside>

        {{-- Sonuç ızgarası --}}
        <div>
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-slate-500">
                    Toplam <strong class="text-brand-900">{{ $campaigns->total() }}</strong> kampanya
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($campaigns as $c)
                    <article class="card group" data-rise>
                        <a href="{{ route('campaigns.show', $c->slug) }}" class="block">
                            <div class="card-media">
                                <div class="absolute inset-0 bg-gradient-to-br from-brand-700/40 via-brand-700/10 to-transparent z-[1]"></div>
                                <img src="{{ $c->cover_image }}" alt="{{ $c->title_tr }}" loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-3 right-3 z-[2] flex gap-1.5">
                                    @if ($c->zakat_eligible)  <span class="badge-z">Z</span> @endif
                                    @if ($c->sadaka_eligible) <span class="badge-sc">SC</span> @endif
                                    @if ($c->fitre_eligible)  <span class="badge-f">F</span> @endif
                                    @if ($c->is_emergency)    <span class="badge-emergency">Acil</span> @endif
                                </div>
                                @if ($c->goal_amount)
                                    <div class="absolute inset-x-3 bottom-3 z-[2] rounded-xl bg-white/95 backdrop-blur p-3 text-xs">
                                        <div class="flex items-center justify-between">
                                            <span class="text-slate-500">Niyet: <strong class="text-brand-900">{{ App\Support\Money::format($c->goal_amount, $c->currency) }}</strong></span>
                                            <span class="font-bold text-emerald-600">%{{ $c->progress_percent }}</span>
                                        </div>
                                        <div class="progress mt-1.5"><div class="bar" style="width: {{ $c->progress_percent }}%"></div></div>
                                    </div>
                                @endif
                            </div>
                        </a>
                        <div class="card-body">
                            <a href="{{ route('campaigns.show', $c->slug) }}" class="block">
                                <h3 class="card-title line-clamp-2 min-h-[3.2rem] group-hover:text-accent-600 transition">{{ $c->title_tr }}</h3>
                                <p class="card-text line-clamp-2">{{ $c->subtitle_tr }}</p>
                                @if ($c->category)
                                    <p class="mt-2 text-[11px] uppercase tracking-wider font-semibold text-brand-500">
                                        {{ $c->category->name_tr }}
                                        @if ($c->country)<span class="text-slate-400"> · {{ $c->country->name_tr }}</span>@endif
                                    </p>
                                @endif
                            </a>
                            <a href="{{ route('donate.show', ['campaign' => $c->slug]) }}" class="btn-accent btn-md w-full mt-5 justify-center">
                                <i data-lucide="heart" class="w-4 h-4"></i> Bağış Yap
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full py-16 text-center text-slate-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                        <p>Bu filtrelerle eşleşen kampanya bulunamadı.</p>
                        <a href="{{ route('campaigns.index') }}" class="btn-outline btn-sm mt-4">Filtreleri sıfırla</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $campaigns->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</section>

@endsection
