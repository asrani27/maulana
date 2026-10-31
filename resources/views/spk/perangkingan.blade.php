@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Perankingan Calon Penerima PKH</h1>
            <p class="text-xs text-slate-500">Urutan prioritas alternatif berdasarkan nilai preferensi SAW</p>
        </div>
        <form method="GET" action="{{ route('spk.perangkingan') }}" class="flex items-center gap-2">
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

    <!-- Action Toolbar -->
    <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-xs flex flex-wrap items-center justify-between gap-3">
        <div class="text-xs text-slate-600">
            Total Evaluasi: <strong class="text-slate-900">{{ count($rankings ?? $ranking ?? []) }} Calon</strong> • Ambang Batas: <strong class="text-emerald-700">V_i ≥ 0.5000</strong>
            @if(!empty($isLocked))
                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                    ✓ Hasil Terkunci di Database
                </span>
            @endif
        </div>
        <div class="flex items-center gap-2">
            @if($periode)
                <form action="{{ route('spk.simpan') }}" method="POST" onsubmit="return confirm('Kunci dan simpan hasil perankingan periode ini ke database?')">
                    @csrf
                    <input type="hidden" name="periode_id" value="{{ $periode->id }}">
                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs">
                        🔒 Kunci & Simpan Hasil SPK
                    </button>
                </form>
                <a href="{{ route('laporan.index', ['periode_id' => $periode->id]) }}" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs shadow-xs">
                    📄 Pratinjau & PDF Laporan
                </a>
            @endif
        </div>
    </div>

    @include('spk.part_ranking_table')
</div>
@endsection

