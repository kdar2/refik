@extends('admin.layout')

@section('title', 'Kullanıcılar')
@section('header', 'Kullanıcılar')

@section('header_actions')
    <a href="{{ route('admin.users.create') }}" class="btn-accent btn-sm">
        <i data-lucide="user-plus" class="w-4 h-4"></i> Yeni Kullanıcı
    </a>
@endsection

@section('content')

<div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
    <form method="GET" action="{{ route('admin.users.index') }}" class="p-4 border-b border-slate-100 grid sm:grid-cols-[1fr_180px_auto] gap-2">
        <input type="search" name="q" value="{{ $q }}" placeholder="İsim veya e-posta ara…" class="input !py-2">
        <select name="role" class="input !py-2">
            <option value="">Tüm roller</option>
            @foreach (['admin','editor','viewer','member'] as $r)
                <option value="{{ $r }}" @selected($role===$r)>{{ ucfirst($r) }}</option>
            @endforeach
        </select>
        <button class="btn-primary btn-sm">Filtrele</button>
    </form>

    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
            <tr>
                <th class="text-left px-4 py-3 font-semibold">Kullanıcı</th>
                <th class="text-left px-4 py-3 font-semibold">Rol</th>
                <th class="text-left px-4 py-3 font-semibold">Telefon</th>
                <th class="text-left px-4 py-3 font-semibold">Kayıt</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($users as $u)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <span class="grid place-items-center w-9 h-9 rounded-full bg-brand-100 text-brand-700 font-bold">
                                {{ strtoupper(mb_substr($u->name, 0, 1)) }}
                            </span>
                            <div>
                                <p class="font-bold text-brand-900">{{ $u->name }}</p>
                                <p class="text-[11px] text-slate-500">{{ $u->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        @switch($u->role)
                            @case('admin')  <span class="badge bg-rose-100 text-rose-700">Admin</span>@break
                            @case('editor') <span class="badge bg-brand-100 text-brand-700">Editör</span>@break
                            @case('viewer') <span class="badge bg-amber-100 text-amber-700">Görüntüleyici</span>@break
                            @default        <span class="badge bg-slate-100 text-slate-700">Üye</span>
                        @endswitch
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-700">{{ $u->phone ?? '—' }}</td>
                    <td class="px-4 py-3 text-xs text-slate-500">{{ $u->created_at?->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.users.edit', $u) }}" class="grid place-items-center w-8 h-8 rounded-lg hover:bg-brand-50 text-brand-700">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            @if ($u->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="inline"
                                      onsubmit="return confirm('Kullanıcı silinecek. Devam edilsin mi?');">
                                    @csrf @method('DELETE')
                                    <button class="grid place-items-center w-8 h-8 rounded-lg hover:bg-rose-50 text-rose-600">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-slate-500 py-10">Kayıt yok.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-slate-100">{{ $users->onEachSide(1)->links() }}</div>
</div>

@endsection
