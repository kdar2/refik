@extends('layouts.app')

@section('title', $title ?? 'Sayfa')

@section('content')
<section class="section bg-surface-alt">
    <div class="container-x max-w-3xl text-center">
        <p class="h-eyebrow">Yapım aşamasında</p>
        <h1 class="mt-2 text-4xl lg:text-5xl font-extrabold font-display text-brand-900">{{ $title ?? 'Sayfa' }}</h1>
        <p class="mt-4 text-slate-600 max-w-xl mx-auto">
            Bu sayfa yakında yayında olacak. <code class="rounded bg-white px-1.5 py-0.5 border border-slate-200">docs/SPEC.md</code>
            içerisindeki ilgili bölümün spesifikasyonunu kullanarak geliştirmesi sırada bekliyor.
        </p>
        <a href="{{ route('home') }}" class="btn-primary btn-md mt-8">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Anasayfaya Dön
        </a>
    </div>
</section>
@endsection
