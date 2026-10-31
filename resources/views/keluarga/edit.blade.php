@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Edit Data Kepala Keluarga</h1>
            <p class="text-xs text-slate-500">Perbarui data: {{ $keluarga->nama_kepala_keluarga }}</p>
        </div>
        <a href="{{ route('keluarga.index') }}" class="text-xs font-semibold text-slate-600 hover:underline">
            ← Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs">
        <form action="{{ route('keluarga.update', $keluarga) }}" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Nomor Kartu Keluarga (KK) *</label>
                    <input type="text" name="no_kk" value="{{ old('no_kk', $keluarga->no_kk) }}" required maxlength="16"
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-1 focus:ring-emerald-500 font-mono">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">NIK Kepala Keluarga *</label>
                    <input type="text" name="nik_kepala_keluarga" value="{{ old('nik_kepala_keluarga', $keluarga->nik_kepala_keluarga) }}" required maxlength="16"
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-1 focus:ring-emerald-500 font-mono">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Nama Kepala Keluarga *</label>
                <input type="text" name="nama_kepala_keluarga" value="{{ old('nama_kepala_keluarga', $keluarga->nama_kepala_keluarga) }}" required
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Alamat Lengkap *</label>
                <textarea name="alamat" rows="2" required
                          class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-1 focus:ring-emerald-500">{{ old('alamat', $keluarga->alamat) }}</textarea>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">RT *</label>
                    <input type="text" name="rt" value="{{ old('rt', $keluarga->rt) }}" required maxlength="5"
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">RW *</label>
                    <input type="text" name="rw" value="{{ old('rw', $keluarga->rw) }}" required maxlength="5"
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Tanggungan *</label>
                    <input type="number" name="jumlah_tanggungan" value="{{ old('jumlah_tanggungan', $keluarga->jumlah_tanggungan) }}" min="0" required
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Pekerjaan</label>
                    <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $keluarga->pekerjaan) }}"
                           class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-1 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Penghasilan Bulanan (Rp)</label>
                <input type="number" name="penghasilan_bulanan" value="{{ old('penghasilan_bulanan', (int)$keluarga->penghasilan_bulanan) }}" min="0" step="10000"
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-1 focus:ring-emerald-500 font-mono">
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Keterangan Tambahan</label>
                <textarea name="keterangan" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-1 focus:ring-emerald-500">{{ old('keterangan', $keluarga->keterangan) }}</textarea>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <a href="{{ route('keluarga.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 font-semibold text-slate-600 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-xs">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
