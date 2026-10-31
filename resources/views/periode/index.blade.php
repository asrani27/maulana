@extends('layouts.app')

@section('content')
<div class="space-y-4" x-data="{ showModal: false }">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Master Periode Penyaluran</h1>
            <p class="text-xs text-slate-500">Kelola gelombang & tahun anggaran seleksi bantuan PKH</p>
        </div>
        <button type="button" @click="showModal = true" class="px-3.5 py-2 rounded-lg bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700">
            + Tambah Periode
        </button>
    </div>

    <!-- Modal Form -->
    <div x-show="showModal" style="display: none;" 
         class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4">
        <div @click.away="showModal = false" class="bg-white rounded-xl max-w-sm w-full p-4 space-y-3">
            <h3 class="text-sm font-bold text-slate-900">Tambah Periode Baru</h3>
            <form action="{{ route('periode.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Periode *</label>
                    <input type="text" name="nama_periode" required placeholder="Contoh: PKH Tahap 2 Tahun 2026"
                           class="w-full px-2.5 py-1.5 rounded border border-slate-300">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tahun Anggaran *</label>
                    <input type="number" name="tahun" value="{{ date('Y') }}" required
                           class="w-full px-2.5 py-1.5 rounded border border-slate-300 font-mono">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="w-full px-2.5 py-1.5 rounded border border-slate-300"></textarea>
                </div>
                <label class="flex items-center gap-2 cursor-pointer font-semibold text-slate-700">
                    <input type="checkbox" name="is_aktif" value="1">
                    <span>Jadikan periode aktif sekarang</span>
                </label>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showModal = false" class="px-3 py-1 rounded border border-slate-300 text-slate-600">Batal</button>
                    <button type="submit" class="px-3 py-1 rounded bg-emerald-600 text-white font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-200">
                <tr>
                    <th class="px-3 py-2.5 w-10 text-center">No</th>
                    <th class="px-3 py-2.5">Nama Periode</th>
                    <th class="px-3 py-2.5 text-center w-20">Tahun</th>
                    <th class="px-3 py-2.5">Keterangan</th>
                    <th class="px-3 py-2.5 text-center w-24">Status</th>
                    <th class="px-3 py-2.5 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($periodeList as $idx => $p)
                    <tr class="hover:bg-slate-50">
                        <td class="px-3 py-2.5 text-center text-slate-400">{{ $idx + 1 }}</td>
                        <td class="px-3 py-2.5 font-bold text-slate-900">{{ $p->nama_periode }}</td>
                        <td class="px-3 py-2.5 text-center font-mono">{{ $p->tahun }}</td>
                        <td class="px-3 py-2.5 text-slate-500">{{ $p->keterangan ?? '-' }}</td>
                        <td class="px-3 py-2.5 text-center">
                            @if($p->is_aktif)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">● Aktif</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">Arsip</span>
                            @endif
                        </td>
                        <td class="px-3 py-2.5 text-center">
                            <div class="inline-flex items-center gap-1">
                                @if(!$p->is_aktif)
                                    <form action="{{ route('periode.activate', $p) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-semibold text-[10px]">Aktifkan</button>
                                    </form>
                                    <form action="{{ route('periode.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus periode ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-0.5 rounded bg-rose-50 text-rose-600 hover:bg-rose-100 font-semibold text-[10px]">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-[10px] text-emerald-700 font-bold">Sedang Aktif</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-400">Belum ada periode.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
