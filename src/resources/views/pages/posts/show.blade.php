@extends('layouts.app')

@section('title',          $post->title_tr)
@section('description',    $post->excerpt_tr ?? \Illuminate\Support\Str::limit(strip_tags($post->content_tr), 160))
@section('og_title',       $post->title_tr)
@section('og_description', $post->excerpt_tr ?? \Illuminate\Support\Str::limit(strip_tags($post->content_tr), 200))
@section('og_image',       $post->cover_image)
@section('og_type',        'article')

@push('schema')
@php
    $_atC = '@' . 'context'; $_atT = '@' . 'type'; $_atI = '@' . 'id';
    $_articleSchema = [
        $_atC           => 'https://schema.org',
        $_atT           => 'NewsArticle',
        'headline'      => $post->title_tr,
        'description'   => $post->excerpt_tr,
        'image'         => $post->cover_image,
        'datePublished' => $post->published_at?->toAtomString(),
        'dateModified'  => $post->updated_at?->toAtomString(),
        'author'        => $post->author ? [
            $_atT  => 'Person',
            'name' => $post->author->name,
        ] : null,
        'publisher' => [
            $_atT  => 'NGO',
            'name' => config('site.legal_name'),
        ],
        'mainEntityOfPage' => [
            $_atT => 'WebPage',
            $_atI => url()->current(),
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($_articleSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')

<article>
    <header class="bg-gradient-to-br from-brand-800 to-brand-900 text-white relative">
        <div class="absolute inset-0 opacity-30">
            <img src="{{ $post->cover_image }}" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-brand-900 via-brand-900/70 to-brand-900/40"></div>
        </div>
        <div class="container-x relative py-16 lg:py-24">
            @if ($post->category)
                <span class="badge bg-accent-500 text-white">{{ $post->category->name_tr }}</span>
            @endif
            <h1 class="mt-4 text-3xl lg:text-5xl font-extrabold font-display tracking-tight max-w-4xl text-balance">
                {{ $post->title_tr }}
            </h1>
            <div class="mt-5 flex flex-wrap items-center gap-4 text-sm text-brand-100/90">
                <span class="flex items-center gap-1.5">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    {{ $post->published_at?->translatedFormat('d F Y') }}
                </span>
                @if ($post->author)
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="user" class="w-4 h-4"></i> {{ $post->author->name }}
                    </span>
                @endif
                <span class="flex items-center gap-1.5">
                    <i data-lucide="eye" class="w-4 h-4"></i> {{ number_format($post->view_count, 0, ',', '.') }} görüntülenme
                </span>
            </div>
        </div>
    </header>

    <section class="section">
        <div class="container-x grid lg:grid-cols-[1fr_300px] gap-10">
            <div data-rise>
                <div class="aspect-[16/9] rounded-3xl overflow-hidden mb-10">
                    <img src="{{ $post->cover_image }}" alt="{{ $post->title_tr }}" class="w-full h-full object-cover">
                </div>

                @if ($post->excerpt_tr)
                    <p class="text-lg text-slate-700 leading-relaxed font-medium border-l-4 border-accent-500 pl-5 mb-8">
                        {{ $post->excerpt_tr }}
                    </p>
                @endif

                <div class="prose prose-slate max-w-none prose-headings:font-display prose-a:text-accent-600">
                    {!! $post->content_tr !!}
                </div>

                {{-- Paylaş --}}
                @php($shareUrl = url()->current())
                @php($shareText = rawurlencode($post->title_tr))
                <div class="mt-12 flex items-center gap-3 pt-6 border-t border-slate-200">
                    <span class="text-sm font-semibold text-slate-700">Paylaş:</span>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ $shareText }}"
                       target="_blank" rel="noopener" class="grid place-items-center w-9 h-9 rounded-full bg-slate-100 hover:bg-brand-50 text-brand-700 transition" aria-label="Twitter / X">
                        <i data-lucide="twitter" class="w-4 h-4"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}"
                       target="_blank" rel="noopener" class="grid place-items-center w-9 h-9 rounded-full bg-slate-100 hover:bg-brand-50 text-brand-700 transition" aria-label="Facebook">
                        <i data-lucide="facebook" class="w-4 h-4"></i>
                    </a>
                    <a href="https://wa.me/?text={{ $shareText }}%20{{ urlencode($shareUrl) }}"
                       target="_blank" rel="noopener" class="grid place-items-center w-9 h-9 rounded-full bg-slate-100 hover:bg-brand-50 text-brand-700 transition" aria-label="WhatsApp">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            {{-- Aside: ilgili haberler --}}
            <aside class="lg:sticky lg:top-24 lg:self-start space-y-3" data-rise>
                @if ($related->count())
                    <h3 class="text-xs uppercase tracking-wider text-brand-500 font-semibold mb-3">İlgili Haberler</h3>
                    @foreach ($related as $r)
                        <a href="{{ route('posts.show', $r->slug) }}"
                           class="group flex gap-3 rounded-xl bg-white border border-slate-100 hover:border-brand-300 hover:shadow-md p-3 transition">
                            <img src="{{ $r->cover_image }}" alt="" class="w-20 h-20 rounded-lg object-cover shrink-0">
                            <div class="min-w-0">
                                <p class="text-xs text-slate-500">{{ $r->published_at?->translatedFormat('d F Y') }}</p>
                                <p class="mt-0.5 text-sm font-bold text-brand-900 line-clamp-3 group-hover:text-accent-600 transition">{{ $r->title_tr }}</p>
                            </div>
                        </a>
                    @endforeach
                @endif
            </aside>
        </div>
    </section>
</article>
@endsection
