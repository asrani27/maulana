@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Laporan Resmi Hasil Seleksi PKH</h1>
            <p class="text-xs text-slate-500">Pratinjau dokumen kedinasan Kantor Desa Thaibah Raya</p>
        </div>
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('laporan.index') }}" class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-700">Periode:</span>
                <select name="periode_id" onchange="this.form.submit()" class="px-2.5 py-1.5 rounded border border-slate-300 text-xs font-semibold">
                    @foreach(($periodes ?? $periodeList ?? []) as $p)
                        <option value="{{ $p->id }}" {{ ($periode && $periode->id == $p->id) ? 'selected' : '' }}>
                            {{ $p->nama_periode }} {{ ($p->status === 'aktif' || $p->is_aktif) ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
            @if($periode)
                <button onclick="window.print()" class="px-3.5 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-xs">
                    🖨️ Cetak
                </button>
                <a href="{{ route('laporan.pdf', ['periode_id' => $periode->id]) }}" target="_blank" class="px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs">
                    📥 Unduh PDF Resmi
                </a>
            @endif
        </div>
    </div>

    <!-- Official Paper Preview -->
    <div class="bg-white rounded-2xl border border-slate-300 shadow-xl p-8 max-w-4xl mx-auto text-slate-900">
        
        @include('laporan.part_letterhead')

        <!-- Tabel Laporan -->
        <table class="w-full border-collapse border border-slate-800 text-xs my-4">
            <thead>
                <tr class="bg-slate-100 font-bold text-slate-900 text-center">
                    <th class="border border-slate-800 p-2 w-10">No</th>
                    <th class="border border-slate-800 p-2">Nama Kepala Keluarga</th>
                    <th class="border border-slate-800 p-2">Nomor KK</th>
                    <th class="border border-slate-800 p-2">Wilayah</th>
                    <th class="border border-slate-800 p-2 w-20">Nilai (V_i)</th>
                    <th class="border border-slate-800 p-2 w-16">Rank</th>
                    <th class="border border-slate-800 p-2 w-24">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($rankings ?? $rankingList ?? []) as $item)
                    @php
                        $kel = is_array($item) ? ($item['keluarga'] ?? null) : ($item->keluarga ?? null);
                        $vi = is_array($item) ? ($item['nilai_akhir'] ?? 0) : ($item->nilai_akhir ?? 0);
                        $rank = is_array($item) ? ($item['ranking'] ?? '-') : ($item->ranking ?? '-');
                        $status = is_array($item) ? ($item['status_kelayakan'] ?? '-') : ($item->status_kelayakan ?? '-');
                    @endphp
                    <tr>
                        <td class="border border-slate-800 p-2 text-center">{{ $loop->iteration }}</td>
                        <td class="border border-slate-800 p-2 font-bold">{{ $kel->nama_kepala_keluarga ?? '-' }}</td>
                        <td class="border border-slate-800 p-2 font-mono text-center">{{ $kel->no_kk ?? '-' }}</td>
                        <td class="border border-slate-800 p-2 text-center">RT {{ $kel->rt ?? '-' }} / RW {{ $kel->rw ?? '-' }}</td>
                        <td class="border border-slate-800 p-2 font-mono text-center font-bold">{{ number_format($vi, 4) }}</td>
                        <td class="border border-slate-800 p-2 text-center font-bold">#{{ $rank }}</td>
                        <td class="border border-slate-800 p-2 text-center font-bold {{ $status === 'Layak' ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $status }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="border border-slate-800 p-4 text-center text-slate-400">Tidak ada data hasil keputusan pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Tanda Tangan Kedinasan -->
        <div class="mt-8 flex justify-end">
            <div class="text-center text-xs w-64 space-y-1">
                <p>{{ $tanggalCetak ?? now()->translatedFormat('d F Y') }}</p>
                <p class="font-bold">Kepala Desa Thaibah Raya (Pambakal),</p>
                <div class="h-16"></div>
                <p class="font-bold underline uppercase">{{ $pambakalNama ?? 'H. Ahmad Mawardi, S.Sos' }}</p>
                <p class="text-[11px] text-slate-500">Pambakal Thaibah Raya</p>
            </div>
        </div>

    </div>
</div>
@endsection
