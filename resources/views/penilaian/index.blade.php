@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Penilaian Calon Penerima PKH</h1>
            <p class="text-xs text-slate-500">Evaluasi alternatif kriteria C1 - C4</p>
        </div>
        @if($periodeAktif)
            <form action="{{ route('penilaian.bulk-auto') }}" method="POST" onsubmit="return confirm('Isi otomatis penilaian yang belum lengkap?')">
                @csrf
                <input type="hidden" name="periode_id" value="{{ $periodeAktif->id }}">
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-xs">
                    ⚡ Auto-Fill Penilaian
                </button>
            </form>
        @endif
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-xs flex items-center justify-between">
        <form method="GET" action="{{ route('penilaian.index') }}" class="flex items-center gap-2">
            <span class="text-xs font-bold text-slate-700">Periode:</span>
            <select name="periode_id" onchange="this.form.submit()" class="px-2.5 py-1 rounded border border-slate-300 text-xs font-semibold">
                @foreach($periodeList as $p)
                    <option value="{{ $p->id }}" {{ ($periodeAktif && $periodeAktif->id == $p->id) ? 'selected' : '' }}>
                        {{ $p->nama_periode }} {{ $p->is_aktif ? '(Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('spk.normalisasi', ['periode_id' => $periodeAktif->id ?? null]) }}" class="px-3 py-1.5 rounded-lg bg-slate-800 text-white text-xs font-bold hover:bg-slate-900">
            Normalisasi →
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-3 py-2 text-center w-10">No</th>
                        <th class="px-3 py-2">Kepala Keluarga</th>
                        <th class="px-3 py-2">No KK</th>
                        @foreach($kriteriaList as $k)
                            <th class="px-3 py-2 text-center">{{ $k->kode }}</th>
                        @endforeach
                        <th class="px-3 py-2 text-center">Status</th>
                        <th class="px-3 py-2 text-center w-16">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($keluargaList as $idx => $k)
                        @php
                            $row = $penilaianMap[$k->id] ?? [];
                            $lengkap = count($row) >= $kriteriaList->count();
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="px-3 py-2 text-center text-slate-400">{{ $keluargaList->firstItem() + $idx }}</td>
                            <td class="px-3 py-2 font-bold text-slate-900">{{ $k->nama_kepala_keluarga }}</td>
                            <td class="px-3 py-2 font-mono text-slate-600">{{ $k->no_kk }}</td>
                            @foreach($kriteriaList as $crit)
                                <td class="px-3 py-2 text-center font-mono font-bold">
                                    {{ isset($row[$crit->id]) ? number_format($row[$crit->id]['nilai'], 2) : '-' }}
                                </td>
                            @endforeach
                            <td class="px-3 py-2 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $lengkap ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $lengkap ? 'Lengkap' : 'Belum' }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-center">
                                <a href="{{ route('penilaian.edit', [$k, 'periode_id' => $periodeAktif->id ?? null]) }}" 
                                   class="px-2 py-0.5 rounded bg-slate-800 text-white font-bold hover:bg-slate-900 text-[10px]">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 4 + $kriteriaList->count() }}" class="px-4 py-6 text-center text-slate-400">Belum ada keluarga.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($keluargaList->hasPages())
            <div class="p-3 border-t border-slate-100">
                {{ $keluargaList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
