@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Perhitungan Nilai Preferensi (W × R)</h1>
            <p class="text-xs text-slate-500">Tahap 3 Perkalian Bobot Kriteria dengan Nilai Ternormalisasi</p>
        </div>
        <form method="GET" action="{{ route('spk.perhitungan') }}" class="flex items-center gap-2">
            <span class="text-xs font-bold text-slate-700">Periode:</span>
            <select name="periode_id" onchange="this.form.submit()" class="px-2.5 py-1 rounded border border-slate-300 text-xs font-semibold">
                @foreach(($periodes ?? $periodeList ?? []) as $p)
                    <option value="{{ $p->id }}" {{ ($periode && $periode->id == $p->id) ? 'selected' : '' }}>
                        {{ $p->nama_periode }} {{ ($p->status === 'aktif' || $p->is_aktif) ? '(Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Vektor Bobot W -->
    <div class="bg-slate-900 text-white rounded-xl p-4 shadow-sm">
        <h2 class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-2">Vektor Bobot Kriteria (W):</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            @foreach(($kriterias ?? $kriteria ?? []) as $crit)
                <div class="p-2.5 rounded-lg bg-slate-800/80 border border-slate-700">
                    <span class="text-slate-400 block font-mono text-[10px]">{{ $crit->kode }} ({{ ucfirst($crit->atribut ?? $crit->jenis) }})</span>
                    <span class="text-sm font-bold text-white font-mono">w_{{ substr($crit->kode, 1) }} = {{ number_format($crit->bobot, 2) }}</span>
                    <span class="block text-[10px] text-slate-400 truncate">{{ $crit->nama_kriteria }}</span>
                </div>
            @endforeach
        </div>
    </div>

    @include('spk.part_preferensi_table')
</div>
@endsection
