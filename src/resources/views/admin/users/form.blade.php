@extends('admin.layout')

@section('title', $mode === 'create' ? 'Yeni Kullanıcı' : 'Kullanıcı Düzenle')
@section('header', $mode === 'create' ? 'Yeni Kullanıcı' : $user->name)

@section('header_actions')
    <a href="{{ route('admin.users.index') }}" class="btn-ghost btn-sm">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Geri
    </a>
@endsection

@section('content')

<form method="POST" action="{{ $mode === 'create' ? route('admin.users.store') : route('admin.users.update', $user) }}"
      class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 lg:p-6 space-y-4 max-w-2xl">
    @csrf
    @if ($mode === 'edit') @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label class="label">Ad Soyad <span class="text-rose-500">*</span></label>
            <input type="text" name="name" required maxlength="120" value="{{ old('name', $user->name) }}" class="input">
            @error('name') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">E-posta <span class="text-rose-500">*</span></label>
            <input type="email" name="email" required value="{{ old('email', $user->email) }}" class="input">
            @error('email') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Telefon</label>
            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="input">
        </div>
        <div>
            <label class="label">Rol <span class="text-rose-500">*</span></label>
            <select name="role" required class="input">
                @foreach ([
                    'admin'   => 'Admin (tüm yetkiler)',
                    'editor'  => 'Editör (içerik düzenleme)',
                    'viewer'  => 'Görüntüleyici (sadece okuma)',
                    'member'  => 'Üye (panel erişimi yok)',
                ] as $val => $label)
                    <option value="{{ $val }}" @selected(old('role', $user->role)===$val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <hr class="border-slate-100">

    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label class="label">{{ $mode === 'create' ? 'Şifre' : 'Yeni Şifre (boş bırakılırsa değişmez)' }}
                @if ($mode === 'create') <span class="text-rose-500">*</span> @endif
            </label>
            <input type="password" name="password" {{ $mode === 'create' ? 'required' : '' }} class="input" autocomplete="new-password">
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Şifre (tekrar)</label>
            <input type="password" name="password_confirmation" class="input" autocomplete="new-password">
        </div>
    </div>

    <button type="submit" class="btn-primary btn-md shadow-brand">
        <i data-lucide="save" class="w-4 h-4"></i>
        {{ $mode === 'create' ? 'Kullanıcıyı Oluştur' : 'Değişiklikleri Kaydet' }}
    </button>
</form>

@endsection
