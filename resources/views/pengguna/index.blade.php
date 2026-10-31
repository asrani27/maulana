@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Manajemen Pengguna</h1>
            <p class="text-xs text-slate-500">Kelola akun administrator dan kepala desa (pambakal)</p>
        </div>
        <a href="{{ route('pengguna.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs">
            <span>+</span> Tambah Pengguna
        </a>
    </div>

    <!-- Search bar -->
    <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-xs">
        <form method="GET" action="{{ route('pengguna.index') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari nama, username, atau email..." 
                   class="flex-1 px-3 py-1.5 rounded-lg border border-slate-300 text-xs focus:ring-1 focus:ring-emerald-500 focus:outline-none">
            <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-slate-800 text-white text-xs font-bold hover:bg-slate-900">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('pengguna.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs text-slate-600 hover:bg-slate-100">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-center w-12">No</th>
                        <th class="px-4 py-3">Nama Lengkap</th>
                        <th class="px-4 py-3">Username</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3 text-center">Peran (Role)</th>
                        <th class="px-4 py-3 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $index => $u)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-center font-medium text-slate-500">{{ $users->firstItem() + $index }}</td>
                            <td class="px-4 py-3 font-bold text-slate-900">{{ $u->nama }}</td>
                            <td class="px-4 py-3 font-mono text-slate-600">{{ $u->username }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $u->email }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($u->role === 'admin')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">Administrator</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800">Kepala Desa (Pambakal)</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('pengguna.edit', $u) }}" class="px-2 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px]">
                                        Edit
                                    </a>
                                    @if(Auth::id() !== $u->id)
                                        <form action="{{ route('pengguna.destroy', $u) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1 rounded bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold text-[11px]">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-slate-400">Tidak ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="p-3 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
