@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Tambah Pengguna Baru</h1>
            <p class="text-xs text-slate-500">Buat akun untuk staf atau pambakal</p>
        </div>
        <a href="{{ route('pengguna.index') }}" class="text-xs font-semibold text-slate-600 hover:underline">
            ← Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs">
        <form action="{{ route('pengguna.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap *</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Username *</label>
                <input type="text" name="username" value="{{ old('username') }}" required
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Peran (Role) *</label>
                <select name="role" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-1 focus:ring-emerald-500">
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="kepala_desa" {{ old('role') === 'kepala_desa' ? 'selected' : '' }}>Kepala Desa (Pambakal)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password *</label>
                <input type="password" name="password" required minlength="6"
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Konfirmasi Password *</label>
                <input type="password" name="password_confirmation" required minlength="6"
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-1 focus:ring-emerald-500">
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <a href="{{ route('pengguna.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 shadow-xs">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
