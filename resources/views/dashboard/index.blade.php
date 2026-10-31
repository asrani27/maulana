@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Hero Banner -->
    <div class="rounded-xl bg-gradient-to-r from-emerald-800 to-teal-800 p-5 text-white shadow">
        <div class="inline-block px-2.5 py-0.5 rounded bg-white/10 text-emerald-200 text-xs font-semibold mb-2">
            Desa Thaibah Raya, Kec. Tatah Makmur, Kab. Banjar
        </div>
        <h1 class="text-xl sm:text-2xl font-bold">Dashboard SPK Seleksi Calon Penerima PKH (Metode SAW)</h1>
        <p class="text-xs sm:text-sm text-emerald-100 mt-1">
            Sistem pendukung keputusan berbasis Simple Additive Weighting secara objektif, transparan, dan akuntabel.
        </p>
    </div>

    <!-- 4 Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs">
            <p class="text-xs font-bold text-slate-500 uppercase">Kepala Keluarga</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalKeluarga) }}</h3>
            <p class="text-[11px] text-slate-400">Warga Terdata</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs">
            <p class="text-xs font-bold text-slate-500 uppercase">Kriteria Aktif</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $totalKriteria }}</h3>
            <p class="text-[11px] text-slate-400">C1 - C4 (Total Bobot 1.00)</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs">
            <p class="text-xs font-bold text-slate-500 uppercase">Periode Aktif</p>
            <h3 class="text-sm font-black text-slate-900 mt-1 truncate">{{ $periodeAktif->nama_periode ?? '-' }}</h3>
            <p class="text-[11px] text-emerald-600 font-semibold">Tahun {{ $periodeAktif->tahun ?? '-' }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs">
            <p class="text-xs font-bold text-slate-500 uppercase">Hasil Perankingan</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalHasilPerankingan) }}</h3>
            <p class="text-[11px] text-slate-400">Calon Dievaluasi</p>
        </div>
    </div>

    <!-- Top 5 Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Top 5 Calon Penerima PKH Periode Aktif</h3>
                <p class="text-xs text-slate-500">Ranking teratas berdasarkan nilai preferensi SAW (V_i)</p>
            </div>
            <a href="{{ route('spk.perangkingan') }}" class="px-3 py-1 rounded-lg bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700">
                Lihat Semua →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-2.5 text-center w-12">Rank</th>
                        <th class="px-4 py-2.5">Nama Kepala Keluarga</th>
                        <th class="px-4 py-2.5">No KK</th>
                        <th class="px-4 py-2.5 text-center">Nilai Akhir (V_i)</th>
                        <th class="px-4 py-2.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($topPenerima as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-2.5 text-center font-bold">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full {{ $item->ranking == 1 ? 'bg-amber-400 text-slate-900' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $item->ranking }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 font-bold text-slate-900">
                                {{ $item->keluarga->nama_kepala_keluarga ?? '-' }}
                                <span class="block text-[11px] font-normal text-slate-500">RT {{ $item->keluarga->rt ?? '-' }} / RW {{ $item->keluarga->rw ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-2.5 font-mono text-slate-600">{{ $item->keluarga->no_kk ?? '-' }}</td>
                            <td class="px-4 py-2.5 text-center font-mono font-bold text-emerald-700">{{ number_format($item->nilai_akhir, 4) }}</td>
                            <td class="px-4 py-2.5 text-center">
                                @if($item->status_kelayakan === 'Layak')
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">✓ Layak</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800">✕ Tidak Layak</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-400">Belum ada data penilaian pada periode aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
